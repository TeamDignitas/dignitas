create table involvement (
  id int not null auto_increment,
  entityId int not null default 0,
  statementId int not null default 0,
  rank int not null default 0,
  createDate int not null default 0,
  modDate int not null default 0,
  modUserId int not null default 0,
  primary key (id),
  key (entityId),
  key (statementId, rank)
);

create table revision_involvement (
  revisionId int not null auto_increment,
  revisionAction varchar(8) not null default 'insert',
  requestId bigint(20) not null default 0,
  id int not null,
  entityId int not null default 0,
  statementId int not null default 0,
  rank int not null default 0,
  createDate int not null default 0,
  modDate int not null default 0,
  modUserId int not null default 0,
  primary key(revisionId),
  key (id),
  key (entityId),
  key (statementId, rank)
);

create trigger involvement_after_insert
  after insert
  on involvement
  for each row
    insert into revision_involvement
    select null, "insert", @request_id, involvement.* from involvement
    where involvement.id = NEW.id;

create trigger involvement_after_update
  after update
  on involvement
  for each row
    insert into revision_involvement
    select null, "update", @request_id, involvement.* from involvement
    where involvement.id = NEW.id;

create trigger involvement_before_delete
  before delete
  on involvement
  for each row
    insert into revision_involvement
    select null, "delete", @request_id, involvement.* from involvement
    where involvement.id = OLD.id;
