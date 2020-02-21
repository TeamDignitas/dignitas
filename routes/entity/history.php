<?php

$id = Request::get('id');

$entity = Entity::get_by_id($id);
if (!$entity) {
  FlashMessage::add(_('info-no-such-entity'));
  Util::redirectToHome();
}

if (!$entity->isViewable()) {
  FlashMessage::add(_('info-restricted-entity'));
  Util::redirectToHome();
}

$title = _('info-entity-history') . ': ' . $entity;

Smart::assign([
  'history' => ObjectDiff::loadFor($entity),
  'title' => $title,
]);

Smart::addResources('history');
Smart::display('bits/history.tpl');
