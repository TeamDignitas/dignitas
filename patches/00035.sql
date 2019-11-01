create table review_log (
  id int not null auto_increment,
  userId int not null default 0,
  reviewId int not null default 0,

  createDate int not null default 0,
  modDate int not null default 0,

  primary key (id),
  key (userId, reviewId)
);

create table history_review_log (
  historyId int not null auto_increment,
  historyAction varchar(8) not null default 'insert',

  id int not null,
  userId int not null default 0,
  reviewId int not null default 0,

  createDate int not null default 0,
  modDate int not null default 0,

  primary key(historyId),
  key (id),
  key (userId, reviewId)
);

create trigger review_log_after_insert
  after insert
  on review_log
  for each row
    insert into history_review_log
    select null, "insert", review_log.* from review_log
    where review_log.id = NEW.id;

create trigger review_log_after_update
  after update
  on review_log
  for each row
    insert into history_review_log
    select null, "update", review_log.* from review_log
    where review_log.id = NEW.id;

create trigger review_log_before_delete
  before delete
  on review_log
  for each row
    insert into history_review_log
    select null, "delete", review_log.* from review_log
    where review_log.id = OLD.id;
