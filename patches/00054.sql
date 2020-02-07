create table user_ext2 (
  id int not null auto_increment,
  userId int not null default 0,
  reputation int not null default 0,
  lastSeen int not null default 0,
  numPendingEdits int not null default 0,

  primary key (id),
  unique key (userId)
);

insert into user_ext
select null, id, reputation, lastSeen, numPendingEdits from user;

alter table user
  drop reputation,
  drop lastSeen,
  drop numPendingEdits;

alter table revision_user
  drop reputation,
  drop lastSeen,
  drop numPendingEdits;
