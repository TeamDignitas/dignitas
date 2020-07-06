alter table entity
  add longPossessive varchar(255) not null after name,
  add shortPossessive varchar(255) not null after longPossessive;

alter table revision_entity
  add longPossessive varchar(255) not null after name,
  add shortPossessive varchar(255) not null after longPossessive;

alter table relation_type
  add phrase int not null default 0 after toEntityTypeId;

alter table revision_relation_type
  add phrase int not null default 0 after toEntityTypeId;
