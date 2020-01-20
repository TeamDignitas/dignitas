<?php

/**
 * Rename history to revision in tables, fields, triggers and indices.
 **/

const GENERIC_STATEMENTS = [
  'rename table history_%1$s to revision_%1$s',

  'alter table revision_%1$s ' .
  'change historyId revisionId int not null auto_increment, ' .
  'change historyAction revisionAction varchar(8) not null default "insert", ' .
  'drop primary key, ' .
  'add primary key(revisionId)',

  'drop trigger if exists %1$s_after_insert',

  'create trigger %1$s_after_insert ' .
  'after insert ' .
  'on %1$s ' .
  'for each row ' .
  'insert into revision_%1$s ' .
  'select null, "insert", @request_id, %1$s.* from %1$s ' .
  'where %1$s.id = NEW.id',

  'drop trigger if exists %1$s_after_update',

  'create trigger %1$s_after_update ' .
  'after update ' .
  'on %1$s ' .
  'for each row ' .
  'insert into revision_%1$s ' .
  'select null, "update", @request_id, %1$s.* from %1$s ' .
  'where %1$s.id = NEW.id',

  'drop trigger if exists %1$s_before_delete',

  'create trigger %1$s_before_delete ' .
  'before delete ' .
  'on %1$s ' .
  'for each row ' .
  'insert into revision_%1$s ' .
  'select null, "delete", @request_id, %1$s.* from %1$s ' .
  'where %1$s.id = OLD.id',
];

    // replace unique keys with non-unique keys
const SPECIFIC_STATEMENTS = [
  'cookie' => [
    'alter table revision_cookie drop index string, add key(string)',
  ],
  'user' => [
    'alter table revision_user drop index email, add key(email)',
  ],
  'variable' => [
    'alter table revision_variable drop index name, add key(name)',
  ],
];

$tables = DB::execute('show tables');

foreach ($tables as $rec) {
  $table = $rec[0];

  if (!preg_match('/^(history|revision)_/', $table)) {
    print "Creating triggers for table {$table}.\n";
    foreach (GENERIC_STATEMENTS as $format) {
      $stmt = sprintf($format, $table);
      print "$stmt\n";
      DB::execute($stmt);
    }
    foreach (SPECIFIC_STATEMENTS[$table] ?? [] as $format) {
      $stmt = sprintf($format, $table);
      print "$stmt\n";
      DB::execute($stmt);
    }
  }
}
