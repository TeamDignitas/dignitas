alter table region
  add depth int not null default 0 after parentId;

alter table revision_region
  add depth int not null default 0 after parentId;
