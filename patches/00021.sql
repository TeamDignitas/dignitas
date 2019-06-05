create table alias (
  id int not null auto_increment,
  entityId int not null default 0,
  name varchar(100) not null default 0,
  rank int not null default 0,
  createDate int not null,
  modDate int default null,
  primary key (id),
  key (entityId)
);
