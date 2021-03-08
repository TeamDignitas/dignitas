#!/usr/bin/php
<?php
/**
 * This script adds or deletes links from the archive backend.
 *
 * Use with -s <M> or --since <M> to only consider objects modified within the
 * last M minutes.
 * Use with -n or --dry-run to see what the script would do without actually
 * executing anything.
 **/

require_once __DIR__ . '/../lib/Core.php';

Log::info('starting');

$opts = getopt('ns:', ['dry-run', 'since:']);
$dryRun = isset($opts['n']) || isset($opts['dry-run']);
$since = (int)($opts['s'] ?? $opts['since'] ?? 0);

foreach (getArchivableClasses() as $class) {
  updateLinks($class, $since, $dryRun);
}

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
 * @param bool $dryRun Print what would happen without changing anything.
 */
function updateLinks(string $class, int $since, bool $dryRun) {
  // load the objects
  $objects = Model::factory($class);

  if ($since) {
    $minTimestamp = time() - $since * 60;
    $objects = $objects->where_gte('modDate', $minTimestamp);
  }

  $objects = $objects->order_by_asc('modDate')->find_many();

  // examine the objects
  foreach ($objects as $obj) {
    Log::info('Analyzing %s#%d (%s)', $class, $obj->id, $obj);

    // extract URLs and add them to a map indexed by URL
    $urls = $obj->getArchivableUrls();
    $newMap = array_fill_keys($urls, true);

    // load the existing archived links
    $als = ArchivedLink::getForObject($obj);
    foreach ($als as $al) {
      if (isset($newMap[$al->url])) {
        // URL is archived and we see it again: keep it. Conveniently, if for any
        // reason we have the URL multiple times, this will only keep one copy.
        unmarkForDeletion($al, $dryRun);
        unset($newMap[$al->url]);
      } else {
        // URL is archived but no longer needed: delete it.
        markForDeletion($al, $dryRun);
      }
    }

    // now schedule for insertion the URLs remaining in $newMap
    foreach ($newMap as $url => $ignored) {
      markForArchival($obj, $url, $dryRun);
    }
  }
}

/**
 * Ensures that an ArchivedLink is marked for deletion.
 */
function markForDeletion(ArchivedLink $al, bool $dryRun) {
  if ($al->status != ArchivedLink::STATUS_DELETED) {
    if ($dryRun) {
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
function unmarkForDeletion(ArchivedLink $al, bool $dryRun) {
  if ($al->status == ArchivedLink::STATUS_DELETED) {
    if ($dryRun) {
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
function markForArchival(Precursor $obj, string $url, bool $dryRun) {
  if ($dryRun) {
    Log::info('  DRY RUN marking for archival: [%s]', $url);
  } else {
    Log::info('  marking for archival: [%s]', $url);
    ArchivedLink::create($obj, $url);
  }
}
