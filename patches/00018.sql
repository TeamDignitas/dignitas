alter table user
  modify aboutMe mediumtext not null default '' after password,
  add lastSeen int not null default 0 after reputation;
