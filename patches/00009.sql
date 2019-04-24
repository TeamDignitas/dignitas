alter table statement
  change `contents` `context` text not null default '',
  add summary text not null default '' after id,
  add goal text not null default '' after context;
