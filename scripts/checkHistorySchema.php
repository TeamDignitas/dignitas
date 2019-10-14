<?php
/**
 * This script goes through every table and checks that a consistent history
 * table exists.
 **/

require_once __DIR__ . '/../lib/Core.php';

const EXPECTED_PROPS = [
  'historyId' => [
    'type' => 'int(11)',
    'null' => 'NO',
    'key' => 'PRI',
    'default' => null,
    'extra' => 'auto_increment',
  ],
  'historyAction' => [
    'type' => 'varchar(8)',
    'null' => 'NO',
    'key' => '',
    'default' => 'insert',
    'extra' => '',
  ],
];

$errors = false;

$tables = DB::execute('show tables');

foreach ($tables as $rec) {
  $table = $rec[0];

  if (preg_match('/^history_/', $table)) {
    // verify that a corresponding data table exists
    $dataTable = substr($table, strlen('history_'));
    if (!DB::tableExists($dataTable)) {
      error("Stray history table {$table}.");
    }
  } else {
    // find the corresponding history table
    $historyTable = 'history_' . $table;
    if (!DB::tableExists($historyTable)) {
      error("No history table for {$table}.");
    } else {

      // get the data and history schemas
      compareSchemas($table, $historyTable);
      checkUniqueKeys($historyTable);
      checkTriggers($table);
    }

    checkCreateModDate($table);
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

function compareSchemas($table, $historyTable) {
  $ds = getSchema($table);
  $hs = getSchema($historyTable);

  // check that each data field has a corresponding history field
  foreach ($ds as $field => $props) {
    if (!isset($hs[$field])) {
      error("Field $table.$field has no corresponding history field.");
    } else {
      compareProps($table, $historyTable, $field, $props, $hs[$field]);
    }
  }

  // check history-specific hields in the history table
  if (!isset($hs['historyId'])) {
    error("Table $historyTable does not have a historyId field.");
  } else {
    checkExpectedProps($historyTable, $hs, 'historyId');
  }
  if (!isset($hs['historyAction'])) {
    error("Table $historyTable does not have a historyAction field.");
  } else {
    checkExpectedProps($historyTable, $hs, 'historyAction');
  }

  // check that each history field has a corresponding data field
  $diff = array_diff_key($hs, $ds, EXPECTED_PROPS);
  foreach ($diff as $field => $ignored) {
    error("Stray history field {$historyTable}.{$field}");
  }
}

function compareProps($table, $historyTable, $field, $dprops, $hprops) {
  // compare data fields against history fields
  foreach ($dprops as $prop => $value) {
    if ($field == 'id' && $prop == 'key') {
      // id should be primary key in the data table, regular key in the history table
      if ($value != 'PRI') {
        error("Field {$table}.id should be primary key.");
      }
      if ($hprops[$prop] != 'MUL') {
        error("Field {$historyTable}.id should have a key.");
      }

    } else if ($field == 'id' && $prop == 'extra') {
      if ($value != 'auto_increment') {
        error("Field {$table}.id should have auto_increment.");
      }
      if ($hprops[$prop] != '') {
        error("Field {$historyTable}.id should not have auto_increment.");
      }

    } else if ($prop == 'key' && $value == 'UNI') {
      if ($hprops[$prop] != 'MUL') {
        error(sprintf('Field %s.%s has a unique key, field %s.%s should have a non-unique key.',
                      $table, $field, $historyTable, $field));
      }

    } else if ($value != $hprops[$prop]) {
      error(sprintf('Field %s.%s has property %s=[%s], while field %s.%s has property %s=[%s].',
                    $table, $field, $prop, $value,
                    $historyTable, $field, $prop, $hprops[$prop]));
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

function checkCreateModDate($table) {
  $schema = getSchema($table);
  if (!isset($schema['createDate'])) {
    error("Table $table should have a createDate field.");
  }
  if (!isset($schema['modDate'])) {
    error("Table $table should have a modDate field.");
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
