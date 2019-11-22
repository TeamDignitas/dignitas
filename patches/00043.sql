alter table entity
  add profile mediumtext not null default '' after userId;

alter table history_entity
  add profile mediumtext not null default '' after userId;

create table entity_link (
  id int not null auto_increment,
  entityId int not null default 0,
  url varchar(1024) not null default '',
  rank int not null default 0,

  createDate int not null default 0,
  modDate int not null default 0,

  primary key (id),
  key (entityId, rank)
);

create table history_entity_link (
  historyId int not null auto_increment,
  historyAction varchar(8) not null default 'insert',

  id int not null,
  entityId int not null default 0,
  url varchar(1024) not null default '',
  rank int not null default 0,

  createDate int not null default 0,
  modDate int not null default 0,

  primary key(historyId),
  key (id),
  key (entityId, rank)
);

create trigger entity_link_after_insert
  after insert
  on entity_link
  for each row
    insert into history_entity_link
    select null, "insert", entity_link.* from entity_link
    where entity_link.id = NEW.id;

create trigger entity_link_after_update
  after update
  on entity_link
  for each row
    insert into history_entity_link
    select null, "update", entity_link.* from entity_link
    where entity_link.id = NEW.id;

create trigger entity_link_before_delete
  before delete
  on entity_link
  for each row
    insert into history_entity_link
    select null, "delete", entity_link.* from entity_link
    where entity_link.id = OLD.id;
