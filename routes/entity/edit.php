<?php

Util::assertLoggedIn();

$id = Request::get('id');
$saveButton = Request::has('saveButton');
$deleteButton = Request::has('deleteButton');

if ($id) {
  $entity = Entity::get_by_id($id);
} else {
  $entity = Model::factory('Entity')->create();
  $entity->userId = User::getActiveId();
}

if ($deleteButton) {
  $entity->delete();
  FlashMessage::add(_('Entity deleted.'), 'success');
  Util::redirectToHome();
}

if ($saveButton) {
  $entity->name = Request::get('name');
  $entity->type = Request::get('type');

  $errors = validate($entity);
  if (empty($errors)) {
    $entity->save();
    FlashMessage::add(_('Changes saved.'), 'success');
    Util::redirect(Router::link('entity/edit') . '/' . $entity->id);
  } else {
    Smart::assign('errors', $errors);
  }
} else {
  // first time loading the page
}

Smart::assign('entity', $entity);
Smart::display('entity/edit.tpl');

/*************************************************************************/

function validate($entity) {
  $errors = [];

  if (!$entity->name) {
    $errors['name'][] = _('Please enter a name.');
  }

  if (!$entity->type) {
    $errors['type'][] = _('Please choose a type.');
  }

  return $errors;
}
