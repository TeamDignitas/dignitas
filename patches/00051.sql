alter table user
  add numPendingEdits int not null default 0 after lastSeen;

alter table revision_user
  add numPendingEdits int not null default 0 after lastSeen;
