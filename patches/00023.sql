update relation
  set startDate = '0000-00-00'
  where startDate is null;

update relation
  set endDate = '0000-00-00'
  where endDate is null;

alter table relation
  change startDate startDate date not null default '0000-00-00',
  change endDate endDate date not null default '0000-00-00';
