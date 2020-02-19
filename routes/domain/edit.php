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
    FlashMessage::add(sprintf(_('Domain «%s» deleted.'), $domain->name), 'success');
    $domain->delete();
    Util::redirectToRoute('domain/list');
  } else {
    FlashMessage::add(
      _('Cannot delete this domain because some links use it.'),
      'danger');
    Util::redirect(Router::getEditLink($domain));
  }
}

if ($cloneButton) {
  if ($id) {
    FlashMessage::add(_('Domain cloned. This is the clone\'s page.'), 'success');
    $clone = $domain->parisClone();
    $clone->name .= ' CLONE';
    $clone->save();
    $clone->copyUploadedFileFrom($domain);
    Util::redirect(Router::getEditLink($clone));
  } else {
    FlashMessage::add(_('Please save the domain before cloning it.'), 'danger');
    Util::redirect(Router::getEditLink($domain));
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

    FlashMessage::add(_('Domain saved.'), 'success');
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
    $errors['name'][] = _('Please enter a domain name.');
  }

  $existing = Domain::get_by_name($domain->name);
  if ($existing && $existing->id != $domain->id) {
    $errors['name'][] = _('A domain by this name already exists.');
  }

  if (!$domain->displayValue) {
    $errors['displayValue'][] = _('Please enter a display value.');
  }

  // image field
  $fileError = UploadTrait::validateFileData($fileData);
  if ($fileError) {
    $errors['image'][] = $fileError;
  }

  return $errors;
}
