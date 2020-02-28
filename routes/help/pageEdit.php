<?php

User::enforceModerator();

$id = Request::get('id');
$saveButton = Request::has('saveButton');
$deleteButton = Request::has('deleteButton');

if ($id) {
  $page = HelpPage::get_by_id($id);
} else {
  $page = Model::factory('HelpPage')->create();
}

if ($deleteButton) {
  Log::notice('deleted help page %d [%s]', $page->id, $page->title);
  FlashMessage::add(_('info-help-page-deleted'), 'success');
  $page->delete();
  Util::redirectToRoute('help/categoryList');
}

if ($saveButton) {
  $page->categoryId = Request::get('categoryId');
  $page->title = Request::get('title');
  $page->path = Request::get('path');
  $page->contents = Request::get('contents');
  $page->assignNewRank();

  $errors = validate($page);
  if (empty($errors)) {
    $page->save();

    FlashMessage::add(_('info-help-page-saved'), 'success');
    Util::redirect(Router::link('help/categoryEdit') . '/' . $page->categoryId);
  } else {
    Smart::assign('errors', $errors);
  }
}

Smart::assign('page', $page);
Smart::addResources('simplemde');
Smart::display('help/pageEdit.tpl');

/*************************************************************************/

function validate($page) {
  $errors = [];

  if (!$page->categoryId) {
    $errors['categoryId'][] = _('info-must-select-help-page-category');
  }

  if (!$page->title) {
    $errors['title'][] = _('info-must-enter-help-page-title');
  }

  if (!$page->path) {
    $errors['path'][] = _('info-must-enter-help-page-path');
  }

  if (!preg_match('/^[-a-z0-9]*$/', $page->path)) {
    $errors['path'][] = _('info-help-path-syntax');
  }

  $existing = HelpPage::get_by_path($page->path);
  if ($existing && $existing->id != $page->id) {
    $errors['path'][] = _('info-help-page-path-taken');
  }

  if (!$page->contents) {
    $errors['contents'][] = _('info-must-enter-help-page-contents');
  }

  return $errors;
}
