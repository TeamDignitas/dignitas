create table subscription (
  id int not null auto_increment,

  userId int not null default 0,
  objectType int not null default 0,
  objectId int not null default 0,
  active bool not null default 1,
  typeMask int not null default 0,
  createDate int not null default 0,

  primary key (id),
  key (userId),
  key (objectType, objectId),
  key (createDate)
);

create table notification (
  id int not null auto_increment,

  userId int not null default 0,
  objectType int not null default 0,
  objectId int not null default 0,
  type int not null default 0,
  seen bool not null default 0,
  createDate int not null default 0,

  primary key (id),
  key (userId),
  key (createDate)
);
