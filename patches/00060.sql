create table invite (
  id int not null auto_increment,

  code varchar(50) not null default '',
  email varchar(255) not null default '',
  senderId int not null default 0,
  receiverId int not null default 0,

  createDate int not null default 0,
  modDate int not null default 0,
  modUserId int not null default 0,

  primary key (id),
  unique key (email)
);

create table revision_invite (
  revisionId int not null auto_increment,
  revisionAction varchar(8) not null default 'insert',
  requestId bigint(20) not null default 0,

  id int not null,

  code varchar(50) not null default '',
  email varchar(255) not null default '',
  senderId int not null default 0,
  receiverId int not null default 0,

  createDate int not null default 0,
  modDate int not null default 0,
  modUserId int not null default 0,

  primary key (revisionId),
  key (id),
  key (email)
);

create trigger invite_after_insert
  after insert
  on invite
  for each row
    insert into revision_invite
    select null, "insert", @request_id, invite.* from invite
    where invite.id = NEW.id;

create trigger invite_after_update
  after update
  on invite
  for each row
    insert into revision_invite
    select null, "update", @request_id, invite.* from invite
    where invite.id = NEW.id;

create trigger invite_before_delete
  before delete
  on invite
  for each row
    insert into revision_invite
    select null, "delete", @request_id, invite.* from invite
    where invite.id = OLD.id;
