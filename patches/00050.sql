create table clone_map (
  id int not null auto_increment,
  rootClass varchar(50) not null default '',
  rootId int not null default 0,
  objectClass varchar(50) not null default '',
  oldId int not null default 0,
  newId int not null default 0,

  createDate int not null default 0,
  modDate int not null default 0,
  modUserId int not null default 0,

  primary key (id),
  key (rootClass, rootId)
);

create table revision_clone_map (
  revisionId int(11) not null auto_increment,
  revisionAction varchar(8) not null default 'insert',
  requestId bigint(20) not null default 0,
  id int(11) not null,
  rootClass varchar(50) not null default '',
  rootId int not null default 0,
  objectClass varchar(50) not null default '',
  oldId int not null default 0,
  newId int not null default 0,

  createDate int not null default 0,
  modDate int not null default 0,
  modUserId int not null default 0,

  primary key (revisionId),
  key (id),
  key (rootClass, rootId)
);

create trigger clone_map_after_insert
  after insert
  on clone_map
  for each row
    insert into revision_clone_map
    select null, "insert", @request_id, clone_map.* from clone_map
    where clone_map.id = NEW.id;

create trigger clone_map_after_update
  after update
  on clone_map
  for each row
    insert into revision_clone_map
    select null, "update", @request_id, clone_map.* from clone_map
    where clone_map.id = NEW.id;

create trigger clone_map_before_delete
  before delete
  on clone_map
  for each row
    insert into revision_clone_map
    select null, "delete", @request_id, clone_map.* from clone_map
    where clone_map.id = OLD.id;
