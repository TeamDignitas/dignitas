create table entity (
  id int not null auto_increment,
  name varchar(255) not null default 0,
  type int not null default 0,
  createDate int not null,
  modDate int default null,
  primary key (id),
  key (name)
);

create table statement (
  id int not null auto_increment,
  contents text not null default '',
  userId int not null default 0,
  entityId int not null default 0,
  createDate int not null,
  modDate int default null,
  primary key (id),
  key (userId),
  key (entityId)
);
