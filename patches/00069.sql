create table ban (
  id int not null auto_increment,

  userId int not null default 0,
  type int not null default 0,
  expiration int not null default 0,

  createDate int not null default 0,
  modDate int not null default 0,
  modUserId int not null default 0,

  primary key (id),
  key (userId, type)
);

create table revision_ban (
  revisionId int not null auto_increment,
  revisionAction varchar(8) not null default 'insert',
  requestId bigint(20) not null default 0,

  id int not null,

  userId int not null default 0,
  type int not null default 0,
  expiration int not null default 0,

  createDate int not null default 0,
  modDate int not null default 0,
  modUserId int not null default 0,

  primary key (revisionId),
  key (id),
  key (userId, type)
);

create trigger ban_after_insert
  after insert
  on ban
  for each row
    insert into revision_ban
    select null, "insert", @request_id, ban.* from ban
    where ban.id = NEW.id;

create trigger ban_after_update
  after update
  on ban
  for each row
    insert into revision_ban
    select null, "update", @request_id, ban.* from ban
    where ban.id = NEW.id;

create trigger ban_before_delete
  before delete
  on ban
  for each row
    insert into revision_ban
    select null, "delete", @request_id, ban.* from ban
    where ban.id = OLD.id;
