alter table user
  add reputation int not null default 0 after password;

update user set reputation = 1;
