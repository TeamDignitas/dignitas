<?php

const ENTITY_LIMIT = 10;

User::enforceModerator();

$id = Request::get('id');
$saveButton = Request::has('saveButton');
$deleteButton = Request::has('deleteButton');

if ($id) {
  $region = Region::get_by_id($id);
} else {
  $region = Model::factory('Region')->create();
}

// regions can be deleted if (1) they have no children and (2) no entities use them
$children = Model::factory('Region')
  ->where('parentId', $region->id)
  ->order_by_asc('name')
  ->find_many();
$used = Entity::get_by_regionId($region->id) || Statement::get_by_regionId($region->id);
$canDelete = empty($children) && !$used;

if ($deleteButton) {
  if ($region->isDeletable() && $canDelete) {
    Snackbar::add(sprintf(_('region-deleted-%s'), $region->name));
    Action::create(Action::TYPE_DELETE, $region);
    $region->delete();
    Util::redirectToRoute('region/list');
  } else {
    Snackbar::add(_('info-cannot-delete-region'));
    Util::redirect("/{$region->id}");
  }
}

if (Ban::exists(Ban::TYPE_TAG)) {
  Snackbar::add(_('info-banned-region'));
  Util::redirectToHome();
}

if ($saveButton) {
  $region->name = Request::get('name');
  $region->parentId = Request::get('parentId', 0);

  $errors = validate($region);
  if (empty($errors)) {
    $new = !$region->id;
    $region->save();

    $parent = Region::get_by_id($region->parentId);
    $depth = $parent ? ($parent->depth + 1) : 0;
    $region->recursiveDepthUpdate($depth);

    Action::create(
      $new ? Action::TYPE_CREATE : Action::TYPE_UPDATE,
      $region);

    Snackbar::add(_('info-region-saved'));
    Util::redirect(Router::link('region/list'));
  } else {
    Smart::assign('errors', $errors);
  }
}

$homonyms = Model::factory('Region')
  ->where('name', $region->name)
  ->where_not_equal('id', $region->id)
  ->find_many();

Smart::assign([
  'r' => $region,
  'children' => $children,
  'regions' => Region::loadAll(),
  'canDelete' => $canDelete,
  'homonyms' => $homonyms,
]);
Smart::display('region/edit.tpl');

/*************************************************************************/

function validate($region) {
  $errors = [];

  if (!$region->name) {
    $errors['name'][] = _('info-must-enter-name');
  }

  // make sure the chosen parent is not also a descendant - no cycles allowed
  $p = $region;
  do {
    $p = Region::get_by_id($p->parentId);
  } while ($p && ($p->id != ($region->id)));
  if ($p) {
    $errors['parentId'][] = _('info-region-loop');
  }

  return $errors;
}
