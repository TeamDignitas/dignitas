create table vote (
  id int not null auto_increment,
  userId int not null default 0,
  value int not null default 0,
  type int not null default 0,
  objectId int not null default 0,
  createDate int not null default 0,
  modDate int not null default 0,

  primary key(id),
  key(type, objectId)
);

alter table statement
  add score int not null default 0 after dateMade;

alter table answer
  add score int not null default 0 after contents;
