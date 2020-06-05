<?php
/**
 * This script goes through all the links and reports two types of problems:
 *
 * 1. Links whose host name does not match the domain name.
 * 2. Unassociated links whose host name matches a domain name.
 *
 * Use with -f or --fix to perform the respective dissociations / associations.
 **/

require_once __DIR__ . '/../lib/Core.php';

LocaleUtil::change('en_US.utf8');

$opts = getopt('f', ['fix']);
$fix = isset($opts['f']) || isset($opts['fix']);

$links = Model::factory('Link')->find_many();

foreach ($links as $l) {
  $hostName = $l->getHostName();

  if ($l->domainId) {
    $d = $l->getDomain();
    if ($hostName != $d->name) {
      Log::notice('Link %d [%s] does not match domain %d [%s].',
                  $l->id, $l->url, $d->id, $d->name);
      $d->domainId = 0;
    }
  }

  if (!$l->domainId) {
    $d = Domain::get_by_name($hostName);
    if ($d) {
      Log::notice('Link %d [%s] matches domain %d [%s].',
                  $l->id, $l->url, $d->id, $d->name);
      $d->domainId = $d->id;
    }
  }

  if ($fix) {
    $l->save();
  }
}
