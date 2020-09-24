#!/usr/bin/php
<?php

/**
 * Closes reviews older than a threshold.
 **/

require_once __DIR__ . '/../lib/Core.php';

const AGE_THRESHOLD = 3 * 86400; // three days

LocaleUtil::change('en_US.utf8');

$maxAge = time() - AGE_THRESHOLD;

$reviews = Model::factory('Review')
  ->where_lt('modDate', $maxAge)
  ->where('status', Review::STATUS_PENDING)
  ->order_by_asc('createDate')
  ->find_many();

foreach ($reviews as $r) {
  $obj = $r->getObject();
  printf("******************************\n");
  printf("Closing review #%d of reason %s\n", $r->id, Review::getUrlName($r->reason));
  printf("  opened %s\n", date('Y-m-d H:i:s', $r->createDate));

  if ($obj) {
    printf("  for %s #%d\n", get_class($obj), $obj->id);
  } else {
    printf("  for unknown object type %d, #%d\n", $r->objectType, $r->objectId);
  }

  $r->resolveUncommon(Review::STATUS_STALE);
}
