<?php

list($numStatementPages, $statements) =
  Search::searchStatements([], Ct::SORT_VERDICT_DATE_DESC, 1);

// load the static resources for the top/bottom of the page
$key = User::getActive() ? 'user' : 'guest';
$staticResourcesTop = StaticResource::addCustomSections("homepage-top-{$key}");
$staticResourcesBottom = StaticResource::addCustomSections("homepage-bottom-{$key}");

Smart::assign([
  'pageType' => 'home',
  'metaDescription' => StaticResource::getLocalizedByName('meta-description.txt'),
  'numStatementPages' => $numStatementPages,
  'statements' => $statements,
  'staticResourcesTop' => $staticResourcesTop,
  'staticResourcesBottom' => $staticResourcesBottom,
]);
Smart::addResources('pagination', 'datepicker', 'bootstrap-select');
Smart::display('aggregate/index.tpl');
