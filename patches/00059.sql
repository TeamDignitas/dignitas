create table canned_response (
  id int not null auto_increment,

  rank int not null default 0,
  contents text not null default '',

  createDate int not null default 0,
  modDate int not null default 0,
  modUserId int not null default 0,

  primary key (id),
  key (rank)
);

create table revision_canned_response (
  revisionId int not null auto_increment,
  revisionAction varchar(8) not null default 'insert',
  requestId bigint(20) not null default 0,

  id int not null,

  rank int not null default 0,
  contents text not null default '',

  createDate int not null default 0,
  modDate int not null default 0,
  modUserId int not null default 0,

  primary key (revisionId),
  key (id),
  key (rank)
);

create trigger canned_response_after_insert
  after insert
  on canned_response
  for each row
    insert into revision_canned_response
    select null, "insert", @request_id, canned_response.* from canned_response
    where canned_response.id = NEW.id;

create trigger canned_response_after_update
  after update
  on canned_response
  for each row
    insert into revision_canned_response
    select null, "update", @request_id, canned_response.* from canned_response
    where canned_response.id = NEW.id;

create trigger canned_response_before_delete
  before delete
  on canned_response
  for each row
    insert into revision_canned_response
    select null, "delete", @request_id, canned_response.* from canned_response
    where canned_response.id = OLD.id;
