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
$numPages = HelpPage::count_by_categoryId($cat->id);
$canDelete = $id && !$numPages;

if ($deleteButton) {
  if ($canDelete) {
    Snackbar::add(sprintf(_('info-help-category-deleted-%s'), $cat->name), 'success');
    $cat->delete();
    Util::redirectToRoute('help/index');
  } else {
    Snackbar::add(_('info-cannot-delete-help-category'), 'danger');
    Util::redirectToSelf();
  }
}

if ($saveButton) {
  $cat->name = Request::get('name');
  $cat->path = Request::get('path');

  $errors = validate($cat);
  if (empty($errors)) {
    $cat->save();

    $ids = Request::getArray('pageIds');
    $rank = 0;
    foreach ($ids as $id) {
      $p = HelpPage::get_by_id($id);
      $p->rank = ++$rank;
      $p->save();
    }

    Snackbar::add(_('info-help-category-saved'), 'success');
    Util::redirect($cat->getViewUrl());
  } else {
    Smart::assign('errors', $errors);
  }
}

Smart::assign([
  'cat' => $cat,
  'canDelete' => $canDelete,
]);
Smart::addResources('sortable');
Smart::display('help/categoryEdit.tpl');

/*************************************************************************/

function validate($cat) {
  $errors = [];

  if (!$cat->name) {
    $errors['name'][] = _('info-must-enter-name');
  }

  if (!$cat->path) {
    $errors['path'][] = _('info-must-enter-help-category-path');
  }

  if (!preg_match('/^[-a-z0-9]*$/', $cat->path)) {
    $errors['path'][] = _('info-help-path-syntax');
  }

  $existing = HelpCategory::get_by_path($cat->path);
  if ($existing && $existing->id != $cat->id) {
    $errors['path'][] = _('info-help-category-path-taken');
  }

  return $errors;
}
