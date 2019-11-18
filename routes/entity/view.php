<?php

$id = Request::get('id');

$entity = Entity::get_by_id($id);
if (!$entity) {
  FlashMessage::add(_('The entity you are looking for does not exist.'));
  Util::redirectToHome();
}

$members = Model::factory('Entity')
  ->table_alias('m')
  ->select('m.*')
  ->distinct()
  ->join('relation', ['m.id', '=', 'r.fromEntityId'], 'r')
  ->where('r.toEntityId', $entity->id)
  ->where('r.type', Relation::TYPE_MEMBER)
  // where_any_is does not work with null values
  ->where_raw('((r.startDate is null) or (r.startDate <= ?))', [ Time::today() ])
  ->where_raw('((r.endDate is null) or (r.endDate >= ?))', [ Time::today() ])
  ->find_many();

Smart::assign([
  'entity' => $entity,
  'aliases' => $entity->getAliases(),
  'relations' => $entity->getRelations(),
  'statements' => $entity->getStatements(),
  'members' => $members,
]);
Smart::display('entity/view.tpl');
