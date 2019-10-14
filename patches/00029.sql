-- replace unique keys by non-unique keys
alter table history_cookie
  drop index `string`,
  add index(string);

alter table history_user
  drop index `email`,
  add index(email);

alter table history_variable
  drop index `name`,
  add index(name);
