create table help_category (
  id int not null auto_increment,

  name varchar(255) not null default '',
  path varchar(255) not null default '',
  rank int not null default 0,

  createDate int not null default 0,
  modDate int not null default 0,
  modUserId int not null default 0,

  primary key (id),
  unique key(path)
);

create table revision_help_category (
  revisionId int not null auto_increment,
  revisionAction varchar(8) not null default 'insert',
  requestId bigint(20) not null default 0,

  id int not null,

  name varchar(255) not null default '',
  path varchar(255) not null default '',
  rank int not null default 0,

  createDate int not null default 0,
  modDate int not null default 0,
  modUserId int not null default 0,

  primary key (revisionId),
  key (id),
  key (path)
);

create trigger help_category_after_insert
  after insert
  on help_category
  for each row
    insert into revision_help_category
    select null, "insert", @request_id, help_category.* from help_category
    where help_category.id = NEW.id;

create trigger help_category_after_update
  after update
  on help_category
  for each row
    insert into revision_help_category
    select null, "update", @request_id, help_category.* from help_category
    where help_category.id = NEW.id;

create trigger help_category_before_delete
  before delete
  on help_category
  for each row
    insert into revision_help_category
    select null, "delete", @request_id, help_category.* from help_category
    where help_category.id = OLD.id;
