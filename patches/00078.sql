alter table statement
  add verdictDate int not null default 0 after verdict;

alter table revision_statement
  add verdictDate int not null default 0 after verdict;

update statement set verdictDate = createDate;
