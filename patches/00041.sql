alter table entity
  add status int not null default 0 after userId,
  add statusUserId int not null default 0 after status,
  add reason int not null default 0 after statusUserId;

alter table history_entity
  add status int not null default 0 after userId,
  add statusUserId int not null default 0 after status,
  add reason int not null default 0 after statusUserId;
