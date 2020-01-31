set @request_id = 0;

create table link (
  id int not null auto_increment,

  objectType int not null default 0,
  objectId int not null default 0,
  url varchar(1024) not null default '',
  rank int not null default 0,

  createDate int not null default 0,
  modDate int not null default 0,
  modUserId int not null default 0,

  primary key (id),
  key (objectType, objectId, rank)
);

create table revision_link (
  revisionId int(11) not null auto_increment,
  revisionAction varchar(8) not null default 'insert',
  requestId bigint(20) not null default 0,

  id int(11) not null,

  objectType int not null default 0,
  objectId int not null default 0,
  url varchar(1024) not null default '',
  rank int not null default 0,

  createDate int not null default 0,
  modDate int not null default 0,
  modUserId int not null default 0,

  primary key (revisionId),
  key (id),
  key (objectType, objectId, rank)
);

create trigger link_after_insert
  after insert
  on link
  for each row
    insert into revision_link
    select null, "insert", @request_id, link.* from link
    where link.id = NEW.id;

create trigger link_after_update
  after update
  on link
  for each row
    insert into revision_link
    select null, "update", @request_id, link.* from link
    where link.id = NEW.id;

create trigger link_before_delete
  before delete
  on link
  for each row
    insert into revision_link
    select null, "delete", @request_id, link.* from link
    where link.id = OLD.id;

-- copy data from existing tables
insert into link
  select null, 4, entityId, url, rank, createDate, modDate, modUserId from entity_link;

insert into link
  select null, 5, relationId, url, rank, createDate, modDate, modUserId from relation_source;

insert into link
  select null, 1, statementId, url, rank, createDate, modDate, modUserId from statement_source;

-- delete old tables
drop table entity_link, revision_entity_link;
drop table relation_source, revision_relation_source;
drop table statement_source, revision_statement_source;
