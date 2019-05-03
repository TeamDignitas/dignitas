create table answer (
  id int not null auto_increment,
  statementId int not null default 0,
  userId int not null default 0,
  contents text not null default '',
  createDate int not null default 0,
  modDate int not null default 0,

  primary key(id),
  key(statementId),
  key(userId)
);
