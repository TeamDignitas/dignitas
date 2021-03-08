create table archived_link (
  id int not null auto_increment,
  status int not null default 0,

  objectType int not null default 0,
  objectId int not null default 0,
  url varchar(1024) not null default '',

  createDate int not null default 0,

  primary key(id),
  key (objectType, objectId)
);
