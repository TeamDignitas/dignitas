<?php

User::enforceModerator();

$id = Request::get('id');
$saveButton = Request::has('saveButton');
$deleteButton = Request::has('deleteButton');

if ($id) {
  $sr = StaticResource::get_by_id($id);
} else {
  $sr = Model::factory('StaticResource')->create();
}

if ($deleteButton) {
  Snackbar::add(sprintf(_('info-static-resource-deleted-%s'), $sr->name));
  $sr->delete();
  Util::redirectToRoute('staticResource/list');
}

if ($saveButton) {
  $sr->name = Request::get('name');
  $sr->locale = Request::get('locale');
  $sr->setContents(Request::get('contents'));
  $fileData = Request::getFile('file', 'StaticResource');

  $errors = validate($sr, $fileData);
  if (empty($errors)) {
    $sr->saveWithFile($fileData);
    Snackbar::add(_('info-static-resource-saved'));
    Util::redirect(Router::link('staticResource/list'));
  } else {
    Smart::assign('errors', $errors);
  }
}

Smart::assign([
  'sr' => $sr,
]);
Smart::addResources('codemirror');
Smart::display('staticResource/edit.tpl');

/*************************************************************************/

function validate($sr, $fileData) {
  $errors = [];

  if (!$sr->name) {
    $errors['name'][] = _('info-must-enter-name');
  }

  $existing = StaticResource::get_by_name_locale($sr->name, $sr->locale);
  if ($existing && $existing->id != $sr->id) {
    $errors['name'][] = _('info-static-resource-exists');
  }

  // borrow some code from UploadTrait even though we don't use the trait
  $fileError = Request::validateFileData($fileData);
  if ($fileError) {
    $errors['file'][] = $fileError;
  }

  $noContents =
    ($fileData['status'] == Request::UPLOAD_NONE) &&
    !$sr->getEditableContents();
  if ($noContents) {
    $errors['file'][] = _('info-no-content-or-uploaded-file');
  }

  return $errors;
}
