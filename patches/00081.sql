-- create the tables for translated help categories
create table help_category_t (
  id int not null auto_increment,
  categoryId int not null default 0,
  locale varchar(20) not null default '',
  name varchar(255) not null default '',
  path varchar(255) not null default '',
  createDate int not null default 0,
  modDate int not null default 0,
  modUserId int not null default 0,
  primary key (id),
  unique key (path),
  key (categoryId, locale)
);

create table revision_help_category_t (
  revisionId int not null auto_increment,
  revisionAction varchar(8) not null default 'insert',
  requestId bigint(20) not null default 0,
  id int not null,
  categoryId int not null default 0,
  locale varchar(20) not null default '',
  name varchar(255) not null default '',
  path varchar(255) not null default '',
  createDate int not null default 0,
  modDate int not null default 0,
  modUserId int not null default 0,
  primary key(revisionId),
  key (id),
  key (path),
  key (categoryId, locale)
);

-- create the tables for translated help pages
create table help_page_t (
  id int not null auto_increment,
  pageId int not null default 0,
  locale varchar(20) not null default '',
  title varchar(255) not null default '',
  path varchar(255) not null default '',
  contents mediumtext not null default '',
  createDate int not null default 0,
  modDate int not null default 0,
  modUserId int not null default 0,
  primary key (id),
  unique key (path),
  key (pageId, locale)
);

create table revision_help_page_t (
  revisionId int not null auto_increment,
  revisionAction varchar(8) not null default 'insert',
  requestId bigint(20) not null default 0,
  id int not null,
  pageId int not null default 0,
  locale varchar(20) not null default '',
  title varchar(255) not null default '',
  path varchar(255) not null default '',
  contents mediumtext not null default '',
  createDate int not null default 0,
  modDate int not null default 0,
  modUserId int not null default 0,
  primary key(revisionId),
  key (id),
  key (path),
  key (pageId, locale)
);

-- migrate the data, including the revisions
set @request_id = 0;

insert into help_category_t
  select id, id, 'ro_RO.utf8', name, path, createDate, modDate, modUserId
  from help_category;

insert into revision_help_category_t
  select revisionId, revisionAction, requestId, id, id, 'ro_RO.utf8', name, path,
    createDate, modDate, modUserId
  from revision_help_category;

insert into help_page_t
  select id, id, 'ro_RO.utf8', title, path, contents, createDate, modDate, modUserId
  from help_page;

insert into revision_help_page_t
  select revisionId, revisionAction, requestId, id, id, 'ro_RO.utf8', title, path, contents,
    createDate, modDate, modUserId
  from revision_help_page;

-- drop the translatable fields from all tables

alter table help_category
  drop name,
  drop path;

alter table revision_help_category
  drop name,
  drop path;

alter table help_page
  drop title,
  drop path,
  drop contents;

alter table revision_help_page
  drop title,
  drop path,
  drop contents;

-- only now create the triggers
create trigger help_category_t_after_insert
  after insert
  on help_category_t
  for each row
    insert into revision_help_category_t
    select null, "insert", @request_id, help_category_t.* from help_category_t
    where help_category_t.id = NEW.id;

create trigger help_category_t_after_update
  after update
  on help_category_t
  for each row
    insert into revision_help_category_t
    select null, "update", @request_id, help_category_t.* from help_category_t
    where help_category_t.id = NEW.id;

create trigger help_category_t_before_delete
  before delete
  on help_category_t
  for each row
    insert into revision_help_category_t
    select null, "delete", @request_id, help_category_t.* from help_category_t
    where help_category_t.id = OLD.id;

create trigger help_page_t_after_insert
  after insert
  on help_page_t
  for each row
    insert into revision_help_page_t
    select null, "insert", @request_id, help_page_t.* from help_page_t
    where help_page_t.id = NEW.id;

create trigger help_page_t_after_update
  after update
  on help_page_t
  for each row
    insert into revision_help_page_t
    select null, "update", @request_id, help_page_t.* from help_page_t
    where help_page_t.id = NEW.id;

create trigger help_page_t_before_delete
  before delete
  on help_page_t
  for each row
    insert into revision_help_page_t
    select null, "delete", @request_id, help_page_t.* from help_page_t
    where help_page_t.id = OLD.id;
