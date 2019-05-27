create table tag (
  id int not null auto_increment,
  parentId int not null default 0,
  value varchar(100) not null default '',
  color varchar(10) not null default '',
  background varchar(10) not null default '',
  icon varchar(50) not null default '',
  iconOnly int not null default 0,
  tooltip varchar(255) not null default '',
  createDate int not null default 0,
  modDate int not null default 0,

  primary key (id),
  key (value),
  key (parentId)
);

create table object_tag (
  id int not null auto_increment,
  objectId int not null default 0,
  objectType int not null default 0,
  tagId int not null default 0,
  rank int not null default 0,
  createDate int not null default 0,
  modDate int not null default 0,
  primary key (id),
  key (objectId, objectType),
  key (tagId)
);
