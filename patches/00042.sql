alter table entity
  add duplicateId int not null default 0 after reason;

alter table history_entity
  add duplicateId int not null default 0 after reason;
