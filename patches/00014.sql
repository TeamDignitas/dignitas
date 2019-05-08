alter table user
  add nickname varchar(255) not null default '' after id;

update user set nickname = email;
