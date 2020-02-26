alter table answer
  add verdict int not null default 0 after contents,
  add proof int not null default 0 after verdict;

alter table revision_answer
  add verdict int not null default 0 after contents,
  add proof int not null default 0 after verdict;

alter table statement
  add verdict int not null default 0 after dateMade;

alter table revision_statement
  add verdict int not null default 0 after dateMade;
