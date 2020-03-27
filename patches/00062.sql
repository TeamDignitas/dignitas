create table loyalty (
  id int not null auto_increment,

  fromEntityId int not null default 0,
  toEntityId int not null default 0,
  value double not null default 0.0,

  primary key (id),
  key (fromEntityId)
);
