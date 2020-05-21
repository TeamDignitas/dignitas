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
$srTop = User::getActive()
  ? null
  : StaticResource::get_by_locale_name('ro_RO.utf8', 'index-top.html');

$srBottom = User::getActive()
  ? null
  : StaticResource::get_by_locale_name('ro_RO.utf8', 'index-bottom.html');

Smart::assign([
  'pageType' => 'home',
  'statements' => $statements,
  'srTop' => $srTop,
  'srBottom' => $srBottom,
]);
Smart::display('aggregate/index.tpl');
