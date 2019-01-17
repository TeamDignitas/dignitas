create table password_token (
  id int not null auto_increment,
  userId int not null,
  token varchar(50) not null,
  createDate int not null,
  modDate int default null,
  primary key (id),
  key (token)
);
