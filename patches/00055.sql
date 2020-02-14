create table domain (
  id int not null auto_increment,

  name varchar(255) not null default '',
  displayValue varchar(255) not null default '',
  fileExtension varchar(10) not null default '',

  createDate int not null default 0,
  modDate int not null default 0,
  modUserId int not null default 0,

  primary key (id),
  unique key(name)
);

create table revision_domain (
  revisionId int not null auto_increment,
  revisionAction varchar(8) not null default 'insert',
  requestId bigint(20) not null default 0,

  id int not null,

  name varchar(255) not null default '',
  displayValue varchar(255) not null default '',
  fileExtension varchar(10) not null default '',

  createDate int not null default 0,
  modDate int not null default 0,
  modUserId int not null default 0,

  primary key (revisionId),
  key (id),
  key (name)
);

create trigger domain_after_insert
  after insert
  on domain
  for each row
    insert into revision_domain
    select null, "insert", @request_id, domain.* from domain
    where domain.id = NEW.id;

create trigger domain_after_update
  after update
  on domain
  for each row
    insert into revision_domain
    select null, "update", @request_id, domain.* from domain
    where domain.id = NEW.id;

create trigger domain_before_delete
  before delete
  on domain
  for each row
    insert into revision_domain
    select null, "delete", @request_id, domain.* from domain
    where domain.id = OLD.id;

alter table link
  add domainId int not null default 0 after rank;
alter table revision_link
  add domainId int not null default 0 after rank;
