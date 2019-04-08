<?php

Util::assertLoggedIn();

$id = Request::get('id');
$saveButton = Request::has('saveButton');

if ($id) {
  $statement = Statement::get_by_id($id);
} else {
  $statement = Model::factory('Statement')->create();
  $statement->userId = User::getActiveId();
}

if ($saveButton) {
  $statement->entityId = Request::get('entityId');
  $statement->contents = Request::get('contents');

  $errors = validate($statement);
  if (empty($errors)) {
    $statement->save();
    FlashMessage::add('Changes saved.', 'success');
    Util::redirect('?id=' . $statement->id);
  } else {
    Smart::assign('errors', $errors);
  }
} else {
  // first time loading the page
}

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
