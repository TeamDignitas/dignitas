alter table entity
  add profile mediumtext not null default '' after userId;

alter table history_entity
  add profile mediumtext not null default '' after userId;
