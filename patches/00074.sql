alter table statement
  add type int not null default 0 after id;

alter table revision_statement
  add type int not null default 0 after id;

set @request_id = 0;
update statement set type = 1;
