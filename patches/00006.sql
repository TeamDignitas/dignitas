create table relation (
  id int not null auto_increment,
  fromEntityId int not null default 0,
  toEntityId int not null default 0,
  type int not null default 0,
  startDate date default null,
  endDate date default null,
  rank int not null default 0,
  createDate int not null,
  modDate int default null,
  primary key (id),
  key (fromEntityId),
  key (toEntityId)
);
