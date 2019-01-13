create table variable (
  id int not null auto_increment,
  name varchar(100) not null,
  value varchar(100) not null,
  createDate int not null,
  modDate int not null,
  primary key (id),
  unique key (name)
);

create table user (
  id int not null auto_increment,
  email varchar(255) not null,
  password varchar(255) not null,
  createDate int not null,
  modDate int not null,
  primary key (id),
  unique key (email)
);
