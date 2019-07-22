create table attachment (
  id int not null auto_increment,
  extension varchar(10) not null default '',
  userId int not null default 0,

  createDate int not null default 0,
  modDate int not null default 0,

  primary key (id)
);

create table object_attachment (
  id int not null auto_increment,
  objectId int not null default 0,
  objectType int not null default 0,
  attachmentId int not null default 0,

  createDate int not null default 0,
  modDate int not null default 0,

  primary key (id),
  key (objectId, objectType),
  key (attachmentId)
);
