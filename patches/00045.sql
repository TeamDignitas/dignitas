alter table user
  add moderator int not null default 0 after reputation;

alter table history_user
  add moderator int not null default 0 after reputation;
