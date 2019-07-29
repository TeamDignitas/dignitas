alter table object_attachment
  change objectType objectClass varchar(100) not null default '';
