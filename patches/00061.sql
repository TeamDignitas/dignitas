alter table review
  add moderator int not null default 0 after duplicateId;
alter table revision_review
  add moderator int not null default 0 after duplicateId;
