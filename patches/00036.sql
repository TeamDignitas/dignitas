alter table flag
  add weight int not null default 0 after details;

alter table history_flag
  add weight int not null default 0 after details;
