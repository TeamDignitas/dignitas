#!/usr/bin/php
<?php
/**
 * Use this script to change the collation of sortable string fields.
 *
 * The default collation is utf8mb4_general_ci, which will produce incorect
 * results for some languages. For example, in Romanian a < ă, so xyzam should
 * come before xyzăl, which utf8mb4_romanian_ci accomplishes, but
 * utf8mb4_general_ci does not.
 *
 * Run this script whenever you notice incorrectly sorted results.
 **/

require_once __DIR__ . '/../lib/Core.php';

$SPECS = [
  [
    'table' => 'entity',
    'column' => 'name',
    'definition' => "varchar(255) not null default ''",
  ],
  [
    'table' => 'region',
    'column' => 'name',
    'definition' => "varchar(100) not null default ''",
  ],
  [
    'table' => 'revision_entity',
    'column' => 'name',
    'definition' => "varchar(255) not null default ''",
  ],
  [
    'table' => 'revision_region',
    'column' => 'name',
    'definition' => "varchar(100) not null default ''",
  ],
];

$DOCUMENTATION = 'https://mariadb.com/kb/en/supported-character-sets-and-collations/#collations';
$DOC_ADVICE = "Please refer to {$DOCUMENTATION} for a list of collations.";

LocaleUtil::change('en_US.utf8');

$collation = ($argv[1] ?? null)
  or die("Usage: {$argv[0]} <collation>\n{$DOC_ADVICE}\n");

// check that the collation exists
$result = DB::execute("show collation like '$collation'");
$numRows = count($result->fetchAll());
$numRows or die("Collation $collation does not exist.\n{$DOC_ADVICE}\n");

foreach ($SPECS as $spec) {
  $cmd = sprintf('alter table %s change %s %s %s collate %s',
                 $spec['table'],
                 $spec['column'],
                 $spec['column'],
                 $spec['definition'],
                 $collation);
  print "Running: $cmd\n";
  DB::execute($cmd);
}
