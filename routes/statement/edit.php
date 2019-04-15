<?php

Util::assertLoggedIn();

$id = Request::get('id');
$saveButton = Request::has('saveButton');
$deleteButton = Request::has('deleteButton');

if ($id) {
  $statement = Statement::get_by_id($id);
} else {
  $statement = Model::factory('Statement')->create();
  $statement->userId = User::getActiveId();
}

if ($deleteButton) {
  $statement->delete();
  FlashMessage::add(_('Statement deleted.'), 'success');
  Util::redirectToHome();
}

if ($saveButton) {
  $statement->entityId = Request::get('entityId');
  $statement->contents = Request::get('contents');

  $errors = validate($statement);
  if (empty($errors)) {
    $statement->save();
    FlashMessage::add(_('Changes saved.'), 'success');
    Util::redirect(Router::link('statement/edit') . '/' . $statement->id);
  } else {
    Smart::assign('errors', $errors);
  }
} else {
  // first time loading the page
}

Smart::addResources('marked', 'select2Dev');
Smart::assign('statement', $statement);
Smart::display('statement/edit.tpl');

/*************************************************************************/

function validate($statement) {
  $errors = [];

  if (!$statement->entityId) {
    $errors['entityId'][] = _('Please enter an author.');
  }

  if (!$statement->contents) {
    $errors['contents'][] = _('Please enter the statement contents.');
  }

  return $errors;
}
