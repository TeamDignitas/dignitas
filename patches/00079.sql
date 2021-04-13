alter table statement
  add regionId int not null default 0 after entityId;

alter table revision_statement
  add regionId int not null default 0 after entityId;
