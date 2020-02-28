create table help_category (
  id int not null auto_increment,

  name varchar(255) not null default '',
  path varchar(255) not null default '',
  rank int not null default 0,

  createDate int not null default 0,
  modDate int not null default 0,
  modUserId int not null default 0,

  primary key (id),
  unique key (path)
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


create table help_page (
  id int not null auto_increment,

  categoryId int not null default 0,
  rank int not null default 0,
  title varchar(255) not null default '',
  path varchar(255) not null default '',
  contents mediumtext not null default '',

  createDate int not null default 0,
  modDate int not null default 0,
  modUserId int not null default 0,

  primary key (id),
  key (categoryId),
  unique key (path)
);

create table revision_help_page (
  revisionId int not null auto_increment,
  revisionAction varchar(8) not null default 'insert',
  requestId bigint(20) not null default 0,

  id int not null,

  categoryId int not null default 0,
  rank int not null default 0,
  title varchar(255) not null default '',
  path varchar(255) not null default '',
  contents mediumtext not null default '',

  createDate int not null default 0,
  modDate int not null default 0,
  modUserId int not null default 0,

  primary key (revisionId),
  key (id),
  key (categoryId),
  key (path)
);

create trigger help_page_after_insert
  after insert
  on help_page
  for each row
    insert into revision_help_page
    select null, "insert", @request_id, help_page.* from help_page
    where help_page.id = NEW.id;

create trigger help_page_after_update
  after update
  on help_page
  for each row
    insert into revision_help_page
    select null, "update", @request_id, help_page.* from help_page
    where help_page.id = NEW.id;

create trigger help_page_before_delete
  before delete
  on help_page
  for each row
    insert into revision_help_page
    select null, "delete", @request_id, help_page.* from help_page
    where help_page.id = OLD.id;
