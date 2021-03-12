create table archived_link (
  id int not null auto_increment,
  status int not null default 0,
  timestamp varchar(50) not null default '',
  path varchar(1024) not null default '',

  objectType int not null default 0,
  objectId int not null default 0,
  url varchar(1024) not null default '',

  createDate int not null default 0,

  primary key(id),
  key (objectType, objectId)
);
