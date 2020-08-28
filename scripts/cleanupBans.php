<?php
/**
 * This script deletes expired bans.
 **/

require_once __DIR__ . '/../lib/Core.php';

LocaleUtil::change('en_US.utf8');

$now = time();
$bans = Model::factory('Ban')
  ->where_not_equal('expiration', Ban::EXPIRATION_NEVER)
  ->where_lt('expiration', $now)
  ->find_many();

foreach ($bans as $ban) {
  Log::info("Deleting expired ban #{$ban->id}");
  $ban->delete();
}
