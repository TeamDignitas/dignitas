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

Smart::assign([
  'region' => $region,
  'entities' => $entities,
  'entityCount' => $entityCount,
]);
Smart::display('region/view.tpl');
