alter table tag
  add visAnon bool not null default true after tooltip;

alter table revision_tag
  add visAnon bool not null default true after tooltip;
