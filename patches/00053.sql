create table comment (
  id int not null auto_increment,

  objectType int not null default 0,
  objectId int not null default 0,
  userId int not null default 0,
  contents text not null default '',
  score int not null default 0,
  status int not null default 0,
  statusUserId int not null default 0,
  reason int not null default 0,

  createDate int not null default 0,
  modDate int not null default 0,
  modUserId int not null default 0,

  primary key (id),
  key (objectType, objectId)
);

create table revision_comment (
  revisionId int not null auto_increment,
  revisionAction varchar(8) not null default 'insert',
  requestId bigint(20) not null default 0,

  id int not null,

  objectType int not null default 0,
  objectId int not null default 0,
  userId int not null default 0,
  contents text not null default '',
  score int not null default 0,
  status int not null default 0,
  statusUserId int not null default 0,
  reason int not null default 0,

  createDate int not null default 0,
  modDate int not null default 0,
  modUserId int not null default 0,

  primary key (revisionId),
  key (id),
  key (objectType, objectId)
);

create trigger comment_after_insert
  after insert
  on comment
  for each row
    insert into revision_comment
    select null, "insert", @request_id, comment.* from comment
    where comment.id = NEW.id;

create trigger comment_after_update
  after update
  on comment
  for each row
    insert into revision_comment
    select null, "update", @request_id, comment.* from comment
    where comment.id = NEW.id;

create trigger comment_before_delete
  before delete
  on comment
  for each row
    insert into revision_comment
    select null, "delete", @request_id, comment.* from comment
    where comment.id = OLD.id;
