<?php

const ENTITY_LIMIT = 10;

$id = Request::get('id');

$region = Region::get_by_id($id);

if (!$region) {
  Snackbar::add(_('info-no-such-region'));
  Util::redirectToHome();
}

$entityCount = Entity::count_by_regionId($region->id);
$entities = Model::factory('Entity')
  ->where('regionId', $region->id)
  ->limit(ENTITY_LIMIT)
  ->find_many();

$query = $region->getStatementQuery();
$statementPages = Statement::getNumPages($query, Statement::REGION_PAGE_SIZE);
$statements = Statement::getPage($query, 1, Statement::REGION_PAGE_SIZE);

Smart::assign([
  'region' => $region,
  'entities' => $entities,
  'entityCount' => $entityCount,
  'statements' => $statements,
  'statementPages' => $statementPages,
]);
Smart::addResources('pagination');
Smart::display('region/view.tpl');
