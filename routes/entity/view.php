<?php

$id = Request::get('id');

$entity = Entity::get_by_id($id);
if (!$entity) {
  FlashMessage::add(_('The entity you are looking for does not exist.'));
  Util::redirectToHome();
}

$statements = Model::factory('Statement')
  ->where('entityId', $id)
  ->order_by_desc('createDate')
  ->limit(10)
  ->find_many();

Smart::assign([
  'entity' => $entity,
  'relations' => $entity->getRelations(),
  'statements' => $statements,
]);
Smart::display('entity/view.tpl');
