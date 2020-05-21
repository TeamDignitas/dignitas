<?php

// not customizable at the moment; move to Config.php if desired
const CAROUSEL_PAGES = 3;
const CAROUSEL_PAGE_SIZE = 4;

// load recent viewable statements
$statements = Model::factory('Statement')
  ->where_not_equal('status', Ct::STATUS_PENDING_EDIT);

if (!User::may(User::PRIV_DELETE_STATEMENT)) {
  $statements = $statements->where_not_equal('status', Ct::STATUS_DELETED);
}

$statements = $statements
  ->order_by_desc('createDate')
  ->limit(CAROUSEL_PAGES * CAROUSEL_PAGE_SIZE)
  ->find_many();

$statements = array_chunk($statements, CAROUSEL_PAGE_SIZE);

// load the static resources for the top/bottom of the page
$key = User::getActive() ? 'user' : 'guest';
$staticResourcesTop = StaticResource::addCustomSections("homepage-top-{$key}");
$staticResourcesBottom = StaticResource::addCustomSections("homepage-bottom-{$key}");

Smart::assign([
  'pageType' => 'home',
  'statements' => $statements,
  'staticResourcesTop' => $staticResourcesTop,
  'staticResourcesBottom' => $staticResourcesBottom,
]);
Smart::display('aggregate/index.tpl');
