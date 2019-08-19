create table flag (
  id int not null auto_increment,
  userId int not null default 0,
  objectType int not null default 0,
  objectId int not null default 0,

  reason int not null default 0,
  duplicateId int not null default 0,
  details text not null default '',
  status int not null default 0,

  createDate int not null default 0,
  modDate int not null default 0,

  primary key (id)
);
