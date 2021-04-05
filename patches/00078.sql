alter table statement
  add verdictDate int not null default 0 after verdict;

alter table revision_statement
  add verdictDate int not null default 0 after verdict;

set @request_id = 0;
update statement set verdictDate = createDate;
