set @request_id = 0;

alter table tag
  add col tinyint unsigned not null default 0 after value;

alter table revision_tag
  add col tinyint unsigned not null default 0 after value;

update tag set col = 1 where background = '#93061b';
update tag set col = 3 where background = '#e3c968';
update tag set col = 5 where background = '';
update tag set col = 6 where background = '#961ec2';
update tag set col = 7 where background = '#b2b2b2';

alter table tag drop color, drop background;
alter table tag rename column col to color;

alter table revision_tag drop color, drop background;
alter table revision_tag rename column col to color;
