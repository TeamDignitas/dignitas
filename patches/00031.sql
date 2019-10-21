alter table attachment_reference
  add objectType int not null default 0 after id;

alter table history_attachment_reference
  add objectType int not null default 0 after id;

update attachment_reference
  set objectType = 1
  where objectClass = 'statement';

update attachment_reference
  set objectType = 2
  where objectClass = 'answer';

alter table attachment_reference
  drop objectClass;

alter table history_attachment_reference
  drop objectClass;
