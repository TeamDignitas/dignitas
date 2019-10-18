create table queue_item (
  id int not null auto_increment,
  objectType int not null default 0,
  objectId int not null default 0,

  queueType int not null default 0,

  createDate int not null default 0,
  modDate int not null default 0,

  primary key (id),
  key (objectType, objectId, queueType)
);

create table history_queue_item (
  historyId int not null auto_increment,
  historyAction varchar(8) not null default 'insert',

  id int not null,
  objectType int not null default 0,
  objectId int not null default 0,

  queueType int not null default 0,

  createDate int not null default 0,
  modDate int not null default 0,

  primary key(historyId),
  key (id),
  key (objectType, objectId, queueType)
);

create trigger queue_item_after_insert
  after insert
  on queue_item
  for each row
    insert into history_queue_item
    select null, "insert", queue_item.* from queue_item
    where queue_item.id = NEW.id;

create trigger queue_item_after_update
  after update
  on queue_item
  for each row
    insert into history_queue_item
    select null, "update", queue_item.* from queue_item
    where queue_item.id = NEW.id;

create trigger queue_item_before_delete
  before delete
  on queue_item
  for each row
    insert into history_queue_item
    select null, "delete", queue_item.* from queue_item
    where queue_item.id = OLD.id;
