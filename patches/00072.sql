create table region (
  id int not null auto_increment,

  parentId int not null default 0,
  name varchar(100) not null default '',

  createDate int not null default 0,
  modDate int not null default 0,
  modUserId int not null default 0,

  primary key (id),
  key (name)
);

create table revision_region (
  revisionId int not null auto_increment,
  revisionAction varchar(8) not null default 'insert',
  requestId bigint(20) not null default 0,

  id int not null,

  parentId int not null default 0,
  name varchar(100) not null default '',

  createDate int not null default 0,
  modDate int not null default 0,
  modUserId int not null default 0,

  primary key (revisionId),
  key (id),
  key (name)
);

create trigger region_after_insert
  after insert
  on region
  for each row
    insert into revision_region
    select null, "insert", @request_id, region.* from region
    where region.id = NEW.id;

create trigger region_after_update
  after update
  on region
  for each row
    insert into revision_region
    select null, "update", @request_id, region.* from region
    where region.id = NEW.id;

create trigger region_before_delete
  before delete
  on region
  for each row
    insert into revision_region
    select null, "delete", @request_id, region.* from region
    where region.id = OLD.id;

alter table entity
  add regionId int not null default 0 after profile,
  add key(regionId);

alter table revision_entity
  add regionId int not null default 0 after profile,
  add key(regionId);
