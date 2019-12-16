rename table queue_item to review;
rename table history_queue_item to history_review;

alter table review
  change queueType reason int not null default 0,
  add status int not null default 0 after reason;

alter table history_review
  change queueType reason int not null default 0,
  add status int not null default 0 after reason;

alter table flag
  drop objectType,
  change objectId reviewId int not null default 0;

alter table history_flag
  drop objectType,
  change objectId reviewId int not null default 0;

drop trigger queue_item_after_insert;
drop trigger queue_item_after_update;
drop trigger queue_item_before_delete;

create trigger review_after_insert
  after insert
  on review
  for each row
    insert into history_review
    select null, "insert", review.* from review
    where review.id = NEW.id;

create trigger review_after_update
  after update
  on review
  for each row
    insert into history_review
    select null, "update", review.* from review
    where review.id = NEW.id;

create trigger review_before_delete
  before delete
  on review
  for each row
    insert into history_review
    select null, "delete", review.* from review
    where review.id = OLD.id;
