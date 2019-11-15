<?php

$id = Request::get('id');
$saveButton = Request::has('saveButton');
$deleteButton = Request::has('deleteButton');
$referrer = Request::get('referrer');

if ($id) {
  $statement = Statement::get_by_id($id);
} else {
  $statement = Model::factory('Statement')->create();
  $statement->dateMade = Time::today();
  $statement->userId = User::getActiveId();
}

if ($deleteButton) {
  User::enforce(User::PRIV_DELETE_STATEMENT);
  $isOwner = ($statement->userId == User::getActiveId());
  $statement->markDeleted($isOwner ? Ct::REASON_BY_OWNER : Ct::REASON_BY_USER);
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

  $tagIds = Request::getArray('tagIds');

  $errors = validate($statement, $sources);
  if (empty($errors)) {
    $new = !$statement->id;
    $statement->save();

    if ($new) {
      Review::checkNewUser($answer);
    }
    StatementSource::updateDependants($sources, 'statementId', $statement->id, 'rank');
    ObjectTag::update($statement, $tagIds);

    FlashMessage::add(
      $new ? _('Statement added.') : _('Statement updated.'),
      'success');
    Util::redirect($referrer);
  } else {
    Smart::assign([
      'errors' => $errors,
      'referrer' => $referrer,
      'sources' =>  $sources,
      'tagIds' => $tagIds,
    ]);
  }
} else {
  // first time loading the page
  Smart::assign([
    'referrer' => Util::getReferrer(),
    'sources' => $statement->getSources(),
    'tagIds' => ObjectTag::getTagIds($statement),
  ]);
}

Smart::addResources('imageModal', 'simplemde', 'sortable');
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
  } else if ($statement->dateMade > Time::today()) {
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
