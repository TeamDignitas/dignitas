<?php

Util::assertLoggedIn();

$id = Request::get('id');
$name = Request::get('name');
$type = Request::get('type');
$saveButton = Request::has('saveButton');

if ($id) {
  $entity = Entity::get_by_id($id);
} else {
  $entity = Model::factory('Entity')->create();
  $entity->userId = User::getActiveId();
}

if ($saveButton) {
  $entity->name = $name;
  $entity->type = $type;

  $errors = validate($entity);
  if (empty($errors)) {
    $entity->save();
    FlashMessage::add('Changes saved.', 'success');
    Util::redirect('?id=' . $entity->id);
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
