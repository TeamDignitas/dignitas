alter table user
  change password password varchar(255) not null default '';

alter table history_user
  change password password varchar(255) not null default '';
