<?php

User::enforceModerator();

$id = Request::get('id');
$saveButton = Request::has('saveButton');
$deleteButton = Request::has('deleteButton');

if ($id) {
  $et = EntityType::get_by_id($id);
} else {
  $et = Model::factory('EntityType')->create();
}

if ($deleteButton) {
  if ($et->canDelete()) {
    FlashMessage::add(sprintf(_('info-entity-type-deleted-%s'), $et->name), 'success');
    $et->delete();
    Util::redirectToRoute('entityType/list');
  } else {
    FlashMessage::add(_('info-cannot-delete-entity-type'), 'danger');
    Util::redirect($et->getEditUrl());
  }
}

if ($saveButton) {
  $et->name = Request::get('name');
  $et->loyaltySource = Request::has('loyaltySource');
  $et->loyaltySink = Request::has('loyaltySink');
  $et->hasColor = Request::has('hasColor');

  $errors = validate($et);
  if (empty($errors)) {
    $et->save();
    FlashMessage::add(_('info-entity-type-saved'), 'success');
    Util::redirect(Router::link('entityType/list'));
  } else {
    Smart::assign('errors', $errors);
  }
}

Smart::assign([
  'et' => $et,
]);
Smart::display('entityType/edit.tpl');

/*************************************************************************/

function validate($et) {
  $errors = [];

  if (!$et->name) {
    $errors['name'][] = _('info-must-enter-name');
  }

  $existing = EntityType::get_by_name($et->name);
  if ($existing && $existing->id != $et->id) {
    $errors['name'][] = _('info-entity-type-name-taken');
  }

  if ($et->loyaltySource && $et->loyaltySink) {
    $errors['loyaltySink'][] = _('info-entity-type-cannot-be-source-and-sink');
  }

  return $errors;
}
