#!/usr/bin/php
<?php
/**
 * This script reports user and entity images that are skewed beyond an
 * acceptable ratio.
 *
 * We don't use the full geometry because it doesn't return any dimensions for
 * SVGs.
 **/

require_once __DIR__ . '/../lib/Core.php';

const ACCEPTABLE_RATIO = 1.05;

LocaleUtil::change('en_US.utf8');

$users = Model::factory('User')
  ->where_not_equal('fileExtension', '')
  ->find_many();

foreach ($users as $u) {
  $size = $u->getFileSize(Config::THUMB_USER_PROFILE);
  if (isSkewed($size)) {
    print "User {$u->nickname} has a skewed image.\n";
  }
}

$entities = Model::factory('Entity')
  ->where_not_equal('fileExtension', '')
  ->find_many();

foreach ($entities as $e) {
  $size = $e->getFileSize(Config::THUMB_ENTITY_LARGE);
  if (isSkewed($size)) {
    printf("Entity %s has a skewed image [%s/%d]\n",
           $e->name, Router::link('entity/edit', true), $e->id);
  }
}

/*************************************************************************/

function isSkewed($size) {
  $ratio = $size['width'] / $size['height'];
  return
    ($ratio > ACCEPTABLE_RATIO) ||
    ($ratio < 1 / ACCEPTABLE_RATIO);
}
