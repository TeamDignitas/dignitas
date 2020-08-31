alter table entity
  change longPossessive longPossessive varchar(255) not null default '',
  change shortPossessive shortPossessive varchar(255) not null default '';

alter table revision_entity
  change longPossessive longPossessive varchar(255) not null default '',
  change shortPossessive shortPossessive varchar(255) not null default '';
