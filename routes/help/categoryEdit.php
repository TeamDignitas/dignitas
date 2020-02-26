<?php

User::enforceModerator();

$id = Request::get('id');
$saveButton = Request::has('saveButton');
$deleteButton = Request::has('deleteButton');

if ($id) {
  $cat = HelpCategory::get_by_id($id);
} else {
  $cat = Model::factory('HelpCategory')->create();
  $cat->rank = 1 + Model::factory('HelpCategory')->count();
}

// categories can be deleted if no links use them
$numPages = 0; // TODO
$canDelete = $id && !$numPages;

if ($deleteButton) {
  if ($canDelete) {
    FlashMessage::add(sprintf(_('info-help-category-deleted'), $cat->name), 'success');
    $cat->delete();
    Util::redirectToRoute('help/categoryList');
  } else {
    FlashMessage::add(_('info-cannot-delete-help-category'), 'danger');
    Util::redirect(Router::link('help/categoryEdit') . '/' . $id);
  }
}

if ($saveButton) {
  $cat->name = Request::get('name');
  $cat->path = Request::get('path');

  $errors = validate($cat);
  if (empty($errors)) {
    $cat->save();

    FlashMessage::add(_('info-help-category-saved'), 'success');
    Util::redirect(Router::link('help/categoryList'));
  } else {
    Smart::assign('errors', $errors);
  }
}

Smart::assign([
  'cat' => $cat,
  'canDelete' => $canDelete,
]);
Smart::display('help/categoryEdit.tpl');

/*************************************************************************/

function validate($cat) {
  $errors = [];

  if (!$cat->name) {
    $errors['name'][] = _('info-must-enter-help-category-name');
  }

  if (!$cat->path) {
    $errors['path'][] = _('info-must-enter-help-category-path');
  }

  $existing = HelpCategory::get_by_path($cat->path);
  if ($existing && $existing->id != $cat->id) {
    $errors['path'][] = _('info-help-category-path-taken');
  }

  return $errors;
}
