alter table flag
  add proposal int not null default 0 after details,
  drop status;

alter table history_flag
  add proposal int not null default 0 after details,
  drop status;
