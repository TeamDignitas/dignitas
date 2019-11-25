create table relation_source (
  id int not null auto_increment,
  relationId int not null default 0,
  url varchar(1024) not null default '',
  rank int not null default 0,

  createDate int not null default 0,
  modDate int not null default 0,

  primary key (id),
  key (relationId, rank)
);

create table history_relation_source (
  historyId int not null auto_increment,
  historyAction varchar(8) not null default 'insert',

  id int not null,
  relationId int not null default 0,
  url varchar(1024) not null default '',
  rank int not null default 0,

  createDate int not null default 0,
  modDate int not null default 0,

  primary key(historyId),
  key (id),
  key (relationId, rank)
);

create trigger relation_source_after_insert
  after insert
  on relation_source
  for each row
    insert into history_relation_source
    select null, "insert", relation_source.* from relation_source
    where relation_source.id = NEW.id;

create trigger relation_source_after_update
  after update
  on relation_source
  for each row
    insert into history_relation_source
    select null, "update", relation_source.* from relation_source
    where relation_source.id = NEW.id;

create trigger relation_source_before_delete
  before delete
  on relation_source
  for each row
    insert into history_relation_source
    select null, "delete", relation_source.* from relation_source
    where relation_source.id = OLD.id;
