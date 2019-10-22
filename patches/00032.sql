alter table vote
  change type objectType int not null default 0;

alter table history_vote
  change type objectType int not null default 0;
