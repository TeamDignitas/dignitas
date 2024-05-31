<?php

const ENTITY_LIMIT = 10;

$id = Request::get('id');

$region = Region::get_by_id($id);

if (!$region) {
  Snackbar::add(_('info-no-such-region'));
  Util::redirectToHome();
}

$query = $region->getEntityQuery();
$entityPages = Entity::getNumPages($query, Entity::REGION_PAGE_SIZE);
$entities = Entity::getPage($query, 1, Entity::REGION_PAGE_SIZE);

$query = $region->getStatementQuery();
$statementPages = Statement::getNumPages($query, Statement::REGION_PAGE_SIZE);
$statements = Statement::getPage($query, 1, Statement::REGION_PAGE_SIZE);

Smart::assign([
  'region' => $region,
  'entities' => $entities,
  'entityPages' => $entityPages,
  'statements' => $statements,
  'statementPages' => $statementPages,
]);
Smart::addResources('pagination');
Smart::display('region/view.tpl');
