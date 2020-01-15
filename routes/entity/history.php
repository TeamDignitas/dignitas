<?php

$id = Request::get('id');

$entity = Entity::get_by_id($id);
if (!$entity) {
  FlashMessage::add(_('The author you are looking for does not exist.'));
  Util::redirectToHome();
}

if (!$entity->isViewable()) {
  FlashMessage::add(_('This author was deleted and is only visible to privileged users.'));
  Util::redirectToHome();
}

$title = _('Entity history for') . ': ' . $entity;

Smart::assign([
  'history' => ObjectDiff::getRevisions($entity),
  'title' => $title,
]);

Smart::addResources('history');
Smart::display('bits/history.tpl');
