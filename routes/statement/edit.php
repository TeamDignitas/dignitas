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

  $sources = buildSources(
    $statement,
    Request::getArray('ssIds'),
    Request::getArray('ssUrls'));

  $errors = validate($statement, $sources);
  if (empty($errors)) {
    $statement->save();
    StatementSource::updateDependants($sources, 'statementId', $statement->id, 'rank');

    FlashMessage::add(_('Changes saved.'), 'success');
    Util::redirectToSelf();
  } else {
    Smart::assign('errors', $errors);
    Smart::assign('sources', $sources);
  }
} else {
  // first time loading the page
  Smart::assign('sources', $statement->getSources());
}

Smart::addResources('marked', 'select2Dev', 'sortable');
Smart::assign('statement', $statement);
Smart::display('statement/edit.tpl');

/*************************************************************************/

function validate($statement, $sources) {
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

  $countBadUrls = 0;
  foreach ($sources as $s) {
    if (!filter_var($s->url, FILTER_VALIDATE_URL)) {
      $countBadUrls++;
    }
  }
  if ($countBadUrls) {
    $errors['sources'][] = _('Some source URLS are invalid.');
  }

  return $errors;
}

function buildSources($statement, $ids, $urls) {
  $result = [];

  foreach ($ids as $i => $id) {
    $ss = $id
      ? StatementSource::get_by_id($id)
      : Model::factory('StatementSource')->create();
    $ss->url = $urls[$i];

    // ignore empty records
    if ($ss->url) {
      $result[] = $ss;
    }
  }

  return $result;
}
