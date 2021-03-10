#!/usr/bin/php
<?php
/**
 * This script brings the ArchivedLink table in sync with the actual contents
 * of our archive. It invokes the archival tool to add or remove URLs from its
 * archive.
 *
 * Use with -n or --dry-run to see what the script would do without actually
 * executing anything.
 **/

require_once __DIR__ . '/../lib/Core.php';

Log::info('starting');

$opts = getopt('n', ['dry-run']);
$dryRun = isset($opts['n']) || isset($opts['dry-run']);

$archiver = getArchiver($dryRun);

$addJobs = getAddJobs();
$archiver->batchAdd($addJobs);

Log::info('finished');

/*************************************************************************/

/**
 * @param bool $dryRun Tell this archiver to print what would happen without changing anything.
 * @return Archiver
 */
function getArchiver($dryRun) {
  $class = Config::ARCHIVER_CLASS;

  if (!class_exists($class)) {
    die("Class {$class} does not exist. Please check Config.php.\n");
  }

  $options = Config::ARCHIVER_OPTIONS;
  $options['dryRun'] = $dryRun;
  return new $class($options);
}

/**
 * Returns a list of ArchivedLinks that have yet to be archived.
 *
 * @return array<ArchivedLink>
 */
function getAddJobs() {
  return Model::factory('ArchivedLink')
    ->where('status', ArchivedLink::STATUS_NEW)
    ->order_by_asc('createDate')
    ->find_many();
}
