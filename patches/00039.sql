alter table statement
  add status int not null default 0 after score,
  add duplicateId int not null default 0 after status;

alter table history_statement
  add status int not null default 0 after score,
  add duplicateId int not null default 0 after status;

alter table answer
  add status int not null default 0 after score;

alter table history_answer
  add status int not null default 0 after score;
