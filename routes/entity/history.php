<?php

$id = Request::get('id');

$entity = Entity::get_by_id($id);
if (!$entity) {
  Snackbar::add(_('info-no-such-entity'));
  Util::redirectToHome();
}

if (!$entity->isViewable()) {
  Snackbar::add(_('info-restricted-entity'));
  Util::redirectToHome();
}

$title = _('info-entity-history') . ': ' . $entity;

Smart::assign([
  'history' => ObjectDiff::loadFor($entity),
  'title' => $title,
  'backButtonText' => _('label-back-to-entity'),
  'backButtonUrl' => $entity->getViewUrl(),
]);

Smart::addResources('history');
Smart::display('bits/history.tpl');
