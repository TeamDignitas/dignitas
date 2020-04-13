create table entity_type (
  id int not null auto_increment,

  name varchar(255) not null default '',
  loyaltySource tinyint not null default 0,
  loyaltySink tinyint not null default 0,
  hasColor tinyint not null default 0,

  createDate int not null default 0,
  modDate int not null default 0,
  modUserId int not null default 0,

  primary key (id),
  key (name)
);

create table revision_entity_type (
  revisionId int not null auto_increment,
  revisionAction varchar(8) not null default 'insert',
  requestId bigint(20) not null default 0,

  id int not null,

  name varchar(255) not null default '',
  loyaltySource tinyint not null default 0,
  loyaltySink tinyint not null default 0,
  hasColor tinyint not null default 0,

  createDate int not null default 0,
  modDate int not null default 0,
  modUserId int not null default 0,

  primary key (revisionId),
  key (id),
  key (name)
);

create trigger entity_type_after_insert
  after insert
  on entity_type
  for each row
    insert into revision_entity_type
    select null, "insert", @request_id, entity_type.* from entity_type
    where entity_type.id = NEW.id;

create trigger entity_type_after_update
  after update
  on entity_type
  for each row
    insert into revision_entity_type
    select null, "update", @request_id, entity_type.* from entity_type
    where entity_type.id = NEW.id;

create trigger entity_type_before_delete
  before delete
  on entity_type
  for each row
    insert into revision_entity_type
    select null, "delete", @request_id, entity_type.* from entity_type
    where entity_type.id = OLD.id;


create table relation_type (
  id int not null auto_increment,

  name varchar(255) not null default '',
  fromEntityTypeId int not null default 0,
  toEntityTypeId int not null default 0,
  weight double not null default 0,
  symmetric tinyint not null default 0,
  membership tinyint not null default 0,
  rank int not null default 0,

  createDate int not null default 0,
  modDate int not null default 0,
  modUserId int not null default 0,

  primary key (id),
  key (rank),
  key (fromEntityTypeId),
  key (toEntityTypeId)
);

create table revision_relation_type (
  revisionId int not null auto_increment,
  revisionAction varchar(8) not null default 'insert',
  requestId bigint(20) not null default 0,

  id int not null,

  name varchar(255) not null default '',
  fromEntityTypeId int not null default 0,
  toEntityTypeId int not null default 0,
  weight double not null default 0,
  symmetric tinyint not null default 0,
  membership tinyint not null default 0,
  rank int not null default 0,

  createDate int not null default 0,
  modDate int not null default 0,
  modUserId int not null default 0,

  primary key (revisionId),
  key (id),
  key (rank),
  key (fromEntityTypeId),
  key (toEntityTypeId)
);

create trigger relation_type_after_insert
  after insert
  on relation_type
  for each row
    insert into revision_relation_type
    select null, "insert", @request_id, relation_type.* from relation_type
    where relation_type.id = NEW.id;

create trigger relation_type_after_update
  after update
  on relation_type
  for each row
    insert into revision_relation_type
    select null, "update", @request_id, relation_type.* from relation_type
    where relation_type.id = NEW.id;

create trigger relation_type_before_delete
  before delete
  on relation_type
  for each row
    insert into revision_relation_type
    select null, "delete", @request_id, relation_type.* from relation_type
    where relation_type.id = OLD.id;

alter table entity
  change type entityTypeId int not null default 0;
alter table revision_entity
  change type entityTypeId int not null default 0;

alter table relation
  change type relationTypeId int not null default 0;
alter table revision_relation
  change type relationTypeId int not null default 0;

set @request_id = 0;
insert into entity_type values
  (null, 'persoană', 1, 0, 0, unix_timestamp(), unix_timestamp(), 1),
  (null, 'partid',   0, 1, 1, unix_timestamp(), unix_timestamp(), 1),
  (null, 'uniune',   0, 0, 1, unix_timestamp(), unix_timestamp(), 1),
  (null, 'site web', 0, 0, 0, unix_timestamp(), unix_timestamp(), 1),
  (null, 'companie', 0, 0, 0, unix_timestamp(), unix_timestamp(), 1);

insert into relation_type values
  (null, 'membru în',           1, 2,  1.0, 0, 1, 1, unix_timestamp(), unix_timestamp(), 1),
  (null, 'asociat la',          1, 5,  0.0, 0, 0, 2, unix_timestamp(), unix_timestamp(), 1),
  (null, 'rudă apropiată cu',   1, 1,  0.5, 1, 0, 3, unix_timestamp(), unix_timestamp(), 1),
  (null, 'rudă îndepărtată cu', 1, 1, 0.25, 1, 0, 4, unix_timestamp(), unix_timestamp(), 1),
  (null, 'membru în',           2, 3,  0.0, 0, 1, 5, unix_timestamp(), unix_timestamp(), 1);

-- there are now two different relation types for person-member-of-party and
-- party-member-of-union
update relation r
  join entity e on r.fromEntityId = e.id
  set r.relationTypeId = 5
  where r.relationTypeId = 1
    and e.entityTypeId = 2;
