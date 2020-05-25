create table action (
  id int not null auto_increment,

  userId int not null default 0,
  createDate int not null default 0,
  type int not null default 0,
  objectType int not null default 0,
  objectId int not null default 0,
  description varchar(255) not null default '',

  primary key (id),
  key (userId),
  key (createDate)
);
