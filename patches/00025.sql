alter table entity
  change imageExtension fileExtension varchar(10) not null default '';

alter table user
  change imageExtension fileExtension varchar(10) not null default '';

alter table attachment
  change extension fileExtension varchar(10) not null default '';
