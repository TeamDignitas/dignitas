alter table flag
  drop reason,
  drop duplicateId,
  change proposal vote boolean not null default true,
  add status int not null default 0 after weight;

alter table history_flag
  drop reason,
  drop duplicateId,
  change proposal vote boolean not null default true,
  add status int not null default 0 after weight;

alter table review
  add duplicateId int not null default 0 after reason;

alter table history_review
  add duplicateId int not null default 0 after reason;
