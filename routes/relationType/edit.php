<?php

User::enforceModerator();

$id = Request::get('id');
$saveButton = Request::has('saveButton');
$deleteButton = Request::has('deleteButton');

if ($id) {
  $rt = RelationType::get_by_id($id);
} else {
  $rt = Model::factory('RelationType')->create();
}

if ($deleteButton) {
  if ($rt->canDelete()) {
    FlashMessage::add(sprintf(_('info-relation-type-deleted-%s'), $rt->name), 'success');
    $rt->delete();
    Util::redirectToRoute('relationType/list');
  } else {
    FlashMessage::add(_('info-cannot-delete-relation-type'), 'danger');
    Util::redirect(Router::getEditLink($rt));
  }
}

if ($saveButton) {
  $rt->name = Request::get('name');
  $rt->fromEntityTypeId = Request::get('fromEntityTypeId');
  $rt->toEntityTypeId = Request::get('toEntityTypeId');
  $rt->weight = floatval(Request::get('weight'));
  $rt->symmetric = Request::has('symmetric');
  $rt->membership = Request::has('membership');
  $rt->assignNewRank();

  $errors = validate($rt);
  if (empty($errors)) {
    $rt->save();
    FlashMessage::add(_('info-relation-type-saved'), 'success');
    Util::redirect(Router::link('relationType/list'));
  } else {
    Smart::assign('errors', $errors);
  }
}

Smart::assign([
  'rt' => $rt,
  'entityTypes' => EntityType::loadAll(),
]);
Smart::display('relationType/edit.tpl');

/*************************************************************************/

function validate($rt) {
  $errors = [];

  if (!$rt->name) {
    $errors['name'][] = _('info-must-enter-name');
  }

  if ($rt->weight < 0.0 || $rt->weight > 1.0) {
    $errors['weight'][] = _('info-relation-type-weight-range');
  }

  if ($rt->symmetric && ($rt->fromEntityTypeId != $rt->toEntityTypeId)) {
    $errors['symmetric'][] = _('info-relation-type-symmetric-same-entity-types');
  }

  return $errors;
}
