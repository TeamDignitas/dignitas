<?php

/**
 * Cleanup all string fields of all tables, which currently:
 *
 *   1. Replaces cedilla with comma in Romanian diacritics: şŞţŢ -> șȘțȚ.
 *   2. Compresses consecutive spaces and tabs.
 **/

processAllModels();

function processAllModels(): void {
  $files = glob(__DIR__ . '/../lib/model/*.php');
  foreach ($files as $file) {
    $class = getClassFromFile($file);
    $table = getTableFromClass($class);
    if ($table) {
      processTable($class, $table);
    } else {
      Log::info("Skipping irregular class {$class}.");
    }
  }
}

function getClassFromFile(string $file): string {
  return basename($file, '.php');
}

function getTableFromClass(string $class): ?string {
  $table = Str::toSnakeCase($class);
  $tableExists = DB::execute("show tables like '$table'")->rowCount();
  return $tableExists ? $table : null;
}

function processTable(string $class, string $table): void {
  $pkExists = DB::execute("show index from {$table} where !non_unique")->rowCount();
  if (!$pkExists) {
    Log::info("Skipping table {$table} because there it has no primary key.");
    return;
  }

  $columns = getStringColumns($table);
  Log::info('Processing table %s, columns %s',
            $table,
            implode(',', $columns));

  cleanupData($class, $columns);
}

function getStringColumns(string $table): array {
  $result = [];
  $columns = DB::execute("show columns from {$table}");

  foreach ($columns as $c) {
    $column = $c['Field'];
    $type = $c['Type'];
    if (Str::startsWith($type, 'char(') ||
        Str::startsWith($type, 'varchar(') ||
        ($type == 'mediumtext') ||
        ($type == 'text')) {
      $result[] = $column;
    }
  }

  return $result;
}

function cleanupData(string $class, array $columns): void {
  $data = Model::factory($class)->find_many();

  foreach ($data as $row) {
    $dirty = false;
    foreach ($columns as $col) {
      $orig = $row->$col;
      $row->$col = Str::cleanup($row->$col);
      if ($row->$col !== $orig) {
        Log::info('%s#%d field %s: [%s]->[%s]',
                  $class, $row->id, $col,
                  Str::shorten($orig, 50),
                  Str::shorten($row->$col, 50));
        $dirty = true;
      }
    }
    if ($dirty) {
      $row->save();
    }
  }
}
