<?php

$id = Request::get('id');
$saveButton = Request::has('saveButton');
$deleteButton = Request::has('deleteButton');

if ($id) {
  $statement = Statement::get_by_id($id);
} else {
  $statement = Model::factory('Statement')->create();
  $statement->dateMade = Util::today();
  $statement->userId = User::getActiveId();
}

if ($deleteButton) {
  User::enforce(User::PRIV_DELETE_STATEMENT);
  $statement->delete();
  FlashMessage::add(_('Statement deleted.'), 'success');
  Util::redirectToHome();
}

if (!$statement->isEditable()) {
  User::enforce($statement->id ? User::PRIV_EDIT_STATEMENT : User::PRIV_ADD_STATEMENT);
}

if ($saveButton) {
  $statement->entityId = Request::get('entityId');
  $statement->summary = Request::get('summary');
  $statement->context = Request::get('context');
  $statement->goal = Request::get('goal');
  $statement->dateMade = Request::get('dateMade');

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

  if (!$statement->summary) {
    $errors['summary'][] = _('Please enter the statement summary.');
  }

  if (!$statement->context) {
    $errors['context'][] = _('Please enter the statement context.');
  }

  if (!$statement->goal) {
    $errors['goal'][] = _('Please enter the statement goal.');
  }

  if (!$statement->dateMade) {
    $errors['dateMade'][] = _('Please enter a date.');
  } else if ($statement->dateMade > Util::today()) {
    $errors['dateMade'][] = _('This date may not be in the future.');
  }

  return $errors;
}
