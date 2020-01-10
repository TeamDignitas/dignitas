<?php

/**
 * These statements are identical for every table except for the table name,
 * so save space by running the statements from PHP.
 **/

const STATEMENTS = [
  'drop table if exists history_%1$s',

  'create table history_%1$s like %1$s',

  'alter table history_%1$s ' .
  'drop primary key, ' .
  'modify column id int not null, ' .
  'add historyId int not null auto_increment first, ' .
  'add historyAction varchar(8) not null default "insert" after historyId, ' .
  'add requestId bigint not null default 0 after historyAction, ' .
  'add primary key(historyId), ' .
  'add key(id)',

  'drop trigger if exists %1$s_after_insert',

  'create trigger %1$s_after_insert ' .
  'after insert ' .
  'on %1$s ' .
  'for each row ' .
  'insert into history_%1$s ' .
  'select null, "insert", @request_id, %1$s.* from %1$s ' .
  'where %1$s.id = NEW.id',

  'drop trigger if exists %1$s_after_update',

  'create trigger %1$s_after_update ' .
  'after update ' .
  'on %1$s ' .
  'for each row ' .
  'insert into history_%1$s ' .
  'select null, "update", @request_id, %1$s.* from %1$s ' .
  'where %1$s.id = NEW.id',

  'drop trigger if exists %1$s_before_delete',

  'create trigger %1$s_before_delete ' .
  'before delete ' .
  'on %1$s ' .
  'for each row ' .
  'insert into history_%1$s ' .
  'select null, "delete", @request_id, %1$s.* from %1$s ' .
  'where %1$s.id = OLD.id',

  // replace unique keys with non-unique keys
  'alter table history_cookie drop index string, add key(string)',
  'alter table history_user drop index email, add key(email)',
  'alter table history_variable drop index name, add key(name)',
];

$tables = DB::execute('show tables');

foreach ($tables as $rec) {
  $table = $rec[0];

  if (!preg_match('/^history_/', $table)) {
    print "Creating triggers for table {$table}.\n";
    foreach (STATEMENTS as $format) {
      $stmt = sprintf($format, $table);
      DB::execute($stmt);
    }
  }
}
