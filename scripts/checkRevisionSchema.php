<?php
/**
 * This script goes through every table and checks that a consistent revision
 * table exists.
 **/

require_once __DIR__ . '/../lib/Core.php';

// these tables, and all those ending in _ext, are not expected to have revisions
const SKIPPED_TABLES = [
  'action',
  'loyalty',
];

const EXPECTED_PROPS = [
  'revisionId' => [
    'type' => 'int(11)',
    'null' => 'NO',
    'key' => 'PRI',
    'default' => null,
    'extra' => 'auto_increment',
  ],
  'revisionAction' => [
    'type' => 'varchar(8)',
    'null' => 'NO',
    'key' => '',
    'default' => 'insert',
    'extra' => '',
  ],
  'requestId' => [
    'type' => 'bigint(20)',
    'null' => 'NO',
    'key' => '',
    'default' => 0,
    'extra' => '',
  ],
];

$errors = false;

$tables = DB::execute('show tables');

foreach ($tables as $rec) {
  $table = $rec[0];

  if (in_array($table, SKIPPED_TABLES) || preg_match('/_ext$/', $table)) {
    // nothing: extension tables have no history
  } else if (preg_match('/^revision_/', $table)) {
    // verify that a corresponding data table exists
    $dataTable = substr($table, strlen('revision_'));
    if (!DB::tableExists($dataTable)) {
      error("Stray revision table {$table}.");
    }
  } else {
    // find the corresponding revision table
    $revisionTable = 'revision_' . $table;
    if (!DB::tableExists($revisionTable)) {
      error("No revision table for {$table}.");
    } else {

      // get the data and revision schemas
      compareSchemas($table, $revisionTable);
      checkUniqueKeys($revisionTable);
      checkTriggers($table);
    }

    checkCreateModFields($table);
  }
}

exit($errors ? 1 : 0);

/*************************************************************************/

function getSchema($table) {
  $dbResult = DB::execute("describe {$table}");
  $schema = [];
  foreach ($dbResult as $row) {
    $schema[$row['Field']] = [
      'type' => $row['Type'],
      'null' => $row['Null'],
      'key' => $row['Key'],
      'default' => $row['Default'],
      'extra' => $row['Extra'],
    ];
  }

  return $schema;
}

function getTriggers($table) {
  $dbResult = DB::execute("show triggers like '{$table}'");

  $result = [];
  foreach ($dbResult as $row) {
    $result[] = $row['Event'];
  }
  return $result;
}

function compareSchemas($table, $revisionTable) {
  $ds = getSchema($table);
  $hs = getSchema($revisionTable);

  // check that each data field has a corresponding revision field
  foreach ($ds as $field => $props) {
    if (!isset($hs[$field])) {
      error("Field $table.$field has no corresponding revision field.");
    } else {
      compareProps($table, $revisionTable, $field, $props, $hs[$field]);
    }
  }

  // check revision-specific hields in the revision table
  if (!isset($hs['revisionId'])) {
    error("Table $revisionTable does not have a revisionId field.");
  } else {
    checkExpectedProps($revisionTable, $hs, 'revisionId');
  }
  if (!isset($hs['revisionAction'])) {
    error("Table $revisionTable does not have a revisionAction field.");
  } else {
    checkExpectedProps($revisionTable, $hs, 'revisionAction');
  }
  if (!isset($hs['requestId'])) {
    error("Table $revisionTable does not have a requestId field.");
  } else {
    checkExpectedProps($revisionTable, $hs, 'requestId');
  }

  // check that each revision field has a corresponding data field
  $diff = array_diff_key($hs, $ds, EXPECTED_PROPS);
  foreach ($diff as $field => $ignored) {
    error("Stray revision field {$revisionTable}.{$field}");
  }
}

function compareProps($table, $revisionTable, $field, $dprops, $hprops) {
  // compare data fields against revision fields
  foreach ($dprops as $prop => $value) {
    if ($field == 'id' && $prop == 'key') {
      // id should be primary key in the data table, regular key in the revision table
      if ($value != 'PRI') {
        error("Field {$table}.id should be primary key.");
      }
      if ($hprops[$prop] != 'MUL') {
        error("Field {$revisionTable}.id should have a key.");
      }

    } else if ($field == 'id' && $prop == 'extra') {
      if ($value != 'auto_increment') {
        error("Field {$table}.id should have auto_increment.");
      }
      if ($hprops[$prop] != '') {
        error("Field {$revisionTable}.id should not have auto_increment.");
      }

    } else if ($prop == 'key' && $value == 'UNI') {
      if ($hprops[$prop] != 'MUL') {
        error(sprintf('Field %s.%s has a unique key, field %s.%s should have a non-unique key.',
                      $table, $field, $revisionTable, $field));
      }

    } else if ($value != $hprops[$prop]) {
      error(sprintf('Field %s.%s has property %s=[%s], while field %s.%s has property %s=[%s].',
                    $table, $field, $prop, $value,
                    $revisionTable, $field, $prop, $hprops[$prop]));
    }
  }
}

function checkExpectedProps($table, $schema, $field) {
  $props = $schema[$field];
  foreach ($props as $prop => $value) {
    $expected = EXPECTED_PROPS[$field][$prop];
    if ($value != $expected) {
      error(sprintf('Field %s.%s has property %s=[%s], expected [%s].',
                    $table, $field, $prop, $value, $expected));
    }
  }
}

function checkUniqueKeys($table) {
  $schema = getSchema($table);
  foreach ($schema as $field => $props) {
    if ($props['key'] == 'UNI') {
      error("Field $table.$field should not have a unique key.");
    }
  }
}

function checkCreateModFields($table) {
  $schema = getSchema($table);
  foreach (['createDate', 'modDate', 'modUserId'] as $field) {
    if (!isset($schema[$field])) {
      error("Table $table should have a $field field.");
    }
  }
}

function checkTriggers($table) {
  $triggers = getTriggers($table);

  // For now we only check that triggers exist for update, insert and delete.
  // We don't check the trigger timing and code.
  foreach (['insert', 'update', 'delete'] as $op) {
    if (!in_array(strtoupper($op), $triggers)) {
      error("Table {$table} does not have a(n) {$op} trigger.");
    }
  }
}

function error($msg) {
  global $errors;

  print "{$msg}\n";
  $errors = true;
}
