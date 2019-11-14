alter table statement
  add statusUserId int not null default 0 after status,
  add reason int not null default 0 after statusUserId;

alter table history_statement
  add statusUserId int not null default 0 after status,
  add reason int not null default 0 after statusUserId;

alter table answer
  add statusUserId int not null default 0 after status,
  add reason int not null default 0 after statusUserId;

alter table history_answer
  add statusUserId int not null default 0 after status,
  add reason int not null default 0 after statusUserId;
