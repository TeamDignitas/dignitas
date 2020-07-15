alter table entity_type
  add isDefault bool not null default 0 after hasColor;

alter table revision_entity_type
  add isDefault bool not null default 0 after hasColor;
