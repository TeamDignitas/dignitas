<?php

// load recent viewable statements
$statements = Model::factory('Statement')
  ->where_not_equal('status', Ct::STATUS_PENDING_EDIT);

if (!User::may(User::PRIV_DELETE_STATEMENT)) {
  $statements = $statements->where_not_equal('status', Ct::STATUS_DELETED);
}

$statements = $statements
  ->order_by_desc('createDate')
  ->limit(Config::CAROUSEL_PAGES * Config::CAROUSEL_ROWS * Config::CAROUSEL_COLUMNS)
  ->find_many();

$carousel = [];
$offset = 0;
for ($p = 0; $p < Config::CAROUSEL_PAGES; $p++) {
  for ($r = 0; $r < Config::CAROUSEL_ROWS; $r++) {
    $slice = array_slice($statements, $offset, Config::CAROUSEL_COLUMNS);
    if (!empty($slice)) {
      $carousel[$p][$r] = $slice;
    }
    $offset += Config::CAROUSEL_COLUMNS;
  }
}

// load the static resources for the top/bottom of the page
$key = User::getActive() ? 'user' : 'guest';
$staticResourcesTop = StaticResource::addCustomSections("homepage-top-{$key}");
$staticResourcesBottom = StaticResource::addCustomSections("homepage-bottom-{$key}");

Smart::assign([
  'pageType' => 'home',
  'carousel' => $carousel,
  'staticResourcesTop' => $staticResourcesTop,
  'staticResourcesBottom' => $staticResourcesBottom,
]);
Smart::display('aggregate/index.tpl');
