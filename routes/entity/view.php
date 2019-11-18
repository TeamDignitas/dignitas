<?php

$id = Request::get('id');

$entity = Entity::get_by_id($id);
if (!$entity) {
  FlashMessage::add(_('The entity you are looking for does not exist.'));
  Util::redirectToHome();
}

Smart::assign([
  'entity' => $entity,
  'statements' => $entity->getStatements(),
]);
Smart::addResources('flag');
Smart::display('entity/view.tpl');
