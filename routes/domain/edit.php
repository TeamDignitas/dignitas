<?php

User::enforceModerator();

$id = Request::get('id');
$saveButton = Request::has('saveButton');
$cloneButton = Request::has('cloneButton');
$deleteButton = Request::has('deleteButton');

if ($id) {
  $domain = Domain::get_by_id($id);
} else {
  $domain = Model::factory('Domain')->create();
}

// domains can be deleted if no links use them
$numLinks = Link::count_by_domainId($domain->id);
$canDelete = $id && !$numLinks;

if ($deleteButton) {
  if ($canDelete) {
    Snackbar::add(sprintf(_('info-domain-deleted-%s'), $domain->name));
    $domain->delete();
    Util::redirectToRoute('domain/list');
  } else {
    Snackbar::add(_('info-cannot-delete-domain'));
    Util::redirect($domain->getEditUrl());
  }
}

if ($cloneButton) {
  if ($id) {
    Snackbar::add(_('info-domain-cloned'));
    $clone = $domain->parisClone();
    $clone->name .= sprintf(' (%s)', _('label-clone'));
    $clone->save();
    $clone->copyUploadedFileFrom($domain);
    Util::redirect($clone->getEditUrl());
  } else {
    // unreachable via normal UI actions
    Snackbar::add(_('info-save-domain-before-clone'));
    Util::redirect($domain->getEditUrl());
  }
}

if ($saveButton) {
  $originalName = $domain->name;
  $domain->name = Request::get('name');
  $domain->displayValue = Request::get('displayValue');
  $deleteImage = Request::has('deleteImage');
  $fileData = Request::getFile('image', 'Domain');

  $errors = validate($domain, $fileData);
  if (empty($errors)) {
    if ($domain->id && ($originalName != $domain->name)) {
      $domain->dissociateLinks();
    }
    $domain->saveWithFile($fileData, $deleteImage);
    $domain->associateLinks();

    Snackbar::add(_('info-domain-saved'));
    Util::redirect(Router::link('domain/list'));
  } else {
    Smart::assign('errors', $errors);
  }
}

Smart::assign([
  'domain' => $domain,
  'canDelete' => $canDelete,
]);
Smart::display('domain/edit.tpl');

/*************************************************************************/

function validate($domain, $fileData) {
  $errors = [];

  if (!$domain->name) {
    $errors['name'][] = _('info-must-enter-name');
  }

  $existing = Domain::get_by_name($domain->name);
  if ($existing && $existing->id != $domain->id) {
    $errors['name'][] = _('info-domain-name-taken');
  }

  if (!$domain->displayValue) {
    $errors['displayValue'][] = _('info-must-enter-domain-display-value');
  }

  // image field
  $fileError = UploadTrait::validateFileData($fileData);
  if ($fileError) {
    $errors['image'][] = $fileError;
  }

  return $errors;
}
