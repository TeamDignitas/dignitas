create table statement_source (
  id int not null auto_increment,
  statementId int not null default 0,
  url varchar(1024) not null default '',
  rank int not null default 0,
  createDate int not null default 0,
  modDate int not null default 0,

  primary key(id),
  key(statementId),
  key(statementId, rank)
);
