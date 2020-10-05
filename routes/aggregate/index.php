<?php

list($numStatementPages, $statements) =
  Search::searchStatements([], Ct::SORT_CREATE_DATE_DESC, 1);

// load the static resources for the top/bottom of the page
$key = User::getActive() ? 'user' : 'guest';
$staticResourcesTop = StaticResource::addCustomSections("homepage-top-{$key}");
$staticResourcesBottom = StaticResource::addCustomSections("homepage-bottom-{$key}");

Smart::assign([
  'pageType' => 'home',
  'numStatementPages' => $numStatementPages,
  'statements' => $statements,
  'staticResourcesTop' => $staticResourcesTop,
  'staticResourcesBottom' => $staticResourcesBottom,
]);
Smart::addResources('pagination', 'datepicker');
Smart::display('aggregate/index.tpl');
