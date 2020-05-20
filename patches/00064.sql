create table static_resource (
  id int not null auto_increment,

  name varchar(255) not null default '',
  locale varchar(20) not null default '',

  createDate int not null default 0,
  modDate int not null default 0,
  modUserId int not null default 0,

  primary key (id),
  key (name)
);

create table revision_static_resource (
  revisionId int not null auto_increment,
  revisionAction varchar(8) not null default 'insert',
  requestId bigint(20) not null default 0,

  id int not null,

  name varchar(255) not null default '',
  locale varchar(20) not null default '',

  createDate int not null default 0,
  modDate int not null default 0,
  modUserId int not null default 0,

  primary key (revisionId),
  key (id),
  key (name)
);

create trigger static_resource_after_insert
  after insert
  on static_resource
  for each row
    insert into revision_static_resource
    select null, "insert", @request_id, static_resource.* from static_resource
    where static_resource.id = NEW.id;

create trigger static_resource_after_update
  after update
  on static_resource
  for each row
    insert into revision_static_resource
    select null, "update", @request_id, static_resource.* from static_resource
    where static_resource.id = NEW.id;

create trigger static_resource_before_delete
  before delete
  on static_resource
  for each row
    insert into revision_static_resource
    select null, "delete", @request_id, static_resource.* from static_resource
    where static_resource.id = OLD.id;
