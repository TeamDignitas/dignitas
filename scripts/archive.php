#!/usr/bin/php
<?php
/**
 * This script examines recent changes, extracts archivable links and
 * adds them to / removes them from the archive.
 *
 * Use with -s <M> or --since <M> to only consider objects modified within the
 * last M minutes. Without this flag, the script examines everything.
 * Use with -n or --dry-run to see what the script would do without actually
 * executing anything.
 **/

require_once __DIR__ . '/../lib/Core.php';

Log::info('starting');

$opts = getopt('ns:', ['dry-run', 'since:']);
$dryRun = isset($opts['n']) || isset($opts['dry-run']);
$since = (int)($opts['s'] ?? $opts['since'] ?? 0);

// first, create/update/delete ArchivedLink objects as needed
foreach (getArchivableClasses() as $class) {
  updateClass($class, $since);
}

// second, run the archiver
$archiver = getArchiver($dryRun);
$removeJobs = getRemoveJobs();
$archiver->remove($removeJobs);
$addJobs = getAddJobs();
$archiver->add($addJobs);

Log::info('finished');

/*************************************************************************/

/**
 * Returns a list of classes that use ArchivableLinksTrait.
 */
function getArchivableClasses() {
  $result = [];

  $files = scandir(__DIR__ . '/../lib/model/');
  foreach ($files as $f) {
    if (Str::endsWith($f, '.php')) {
      $class = substr($f, 0, -4); // strip '.php'
      $uses = class_uses($class);

      if (isset($uses['ArchivableLinksTrait'])) {
        $result[] = $class;
      }
    }
  }

  return $result;
}

/**
 * Extracts links from relevant objects of class $class and schedules them for
 * archival or deletion when necessary.
 *
 * @param string $class A class name.
 * @param int $since Consider objects modified at most $since minutes ago, or
 *   all objects if $since = 0.
 */
function updateClass(string $class, int $since) {
  // load the objects
  $objects = Model::factory($class);

  if ($since) {
    $minTimestamp = time() - $since * 60;
    $objects = $objects->where_gte('modDate', $minTimestamp);
  }

  $objects = $objects->order_by_asc('modDate')->find_many();

  // examine the objects
  foreach ($objects as $obj) {
    updateLinks($obj);
  }
}

/**
 * Extracts links from one object containing archivable URLs.
 */
function updateLinks($obj) {
  Log::debug('Analyzing %s#%d (%s)', get_class($obj), $obj->id, $obj);

  // extract URLs and add them to a map indexed by URL
  $urls = $obj->getArchivableUrls();
  $newMap = array_fill_keys($urls, true);

  // load the existing archived links
  $als = ArchivedLink::getForObject($obj);
  foreach ($als as $al) {
    if (isset($newMap[$al->url])) {
      // URL is archived and we see it again: keep it. Conveniently, if for any
      // reason we have the URL multiple times, this will only keep one copy.
      unmarkForDeletion($al);
      unset($newMap[$al->url]);
    } else {
      // URL is archived but no longer needed: delete it.
      markForDeletion($al);
    }
  }

  // now schedule for insertion the URLs remaining in $newMap
  foreach ($newMap as $url => $ignored) {
    markForArchival($obj, $url);
  }
}

/**
 * Ensures that an ArchivedLink is marked for deletion.
 */
function markForDeletion(ArchivedLink $al) {
  if ($al->status != ArchivedLink::STATUS_DELETED) {
    if ($GLOBALS['dryRun']) {
      Log::info('  DRY RUN marking for deletion: [%s]', $al->url);
    } else {
      Log::info('  marking for deletion: [%s]', $al->url);
      $al->markForDeletion();
    }
  }
}

/**
 * Ensures that an ArchivedLink will not be deleted.
 */
function unmarkForDeletion(ArchivedLink $al) {
  if ($al->status == ArchivedLink::STATUS_DELETED) {
    if ($GLOBALS['dryRun']) {
      Log::info('  DRY RUN unmarking for deletion: [%s]', $al->url);
    } else {
      Log::info('  unmarking for deletion: [%s]', $al->url);
      $al->unmarkForDeletion();
    }
  }
}

/**
 * Creates a new ArchivedLink.
 *
 * @param Precursor $obj Object containing the URL.
 */
function markForArchival(Precursor $obj, string $url) {
  if ($GLOBALS['dryRun']) {
    Log::info('  DRY RUN marking for archival: [%s]', $url);
  } else {
    Log::info('  marking for archival: [%s]', $url);
    ArchivedLink::create($obj, $url);
  }
}

/**
 * @param bool $dryRun Tell this archiver to print what would happen without changing anything.
 * @return Archiver
 */
function getArchiver($dryRun) {
  $class = Config::ARCHIVER_CLASS;
  Log::info('creating a new archiver of type %s', $class);

  if (!class_exists($class)) {
    $msg = "Class {$class} does not exist. Please check Config.php.";
    Log::error($msg);
    die("{$msg}\n");
  }

  $options = Config::ARCHIVER_OPTIONS;
  if ($dryRun) {
    // Passing -n overrides the archiver's dryRun value in Config.php.
    $options['dryRun'] = true;
  }
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

/**
 * Returns a list of ArchivedLinks that should be deleted.
 *
 * If possible, peforms safe deletions which do not involve the archiver.
 * Specifically, if multiple articles link to the same URL and only some of
 * them are deleted, then those can be removed from the database without
 * talking to the archiver. We keep the underlying archive for the links which
 * are not deleted.
 *
 * @return array<ArchivedLink>
 */
function getRemoveJobs() {
  // grab deleted links with matching non-deleted lins
  $als = Model::factory('ArchivedLink')
    ->where('status', ArchivedLink::STATUS_DELETED)
    ->where_raw('url in (select url from archived_link where status != ?)',
                [ ArchivedLink::STATUS_DELETED ])
    ->find_many();

  foreach ($als as $al) {
    if ($GLOBALS['dryRun']) {
      Log::info('  DRY RUN deleting orphan: [%s]', $al->url);
    } else {
      Log::info('  deleting orphan: [%s]', $al->url);
      $al->delete();
    }
  }

  // every other deleted link should go through the archiver
  return Model::factory('ArchivedLink')
    ->where('status', ArchivedLink::STATUS_DELETED)
    ->order_by_asc('createDate')
    ->find_many();
}
