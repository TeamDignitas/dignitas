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
  if (!$statement->isDeletable()) {
    FlashMessage::add(_('You have insufficient privileges to delete this statement.'));
    Util::redirectToSelf();
  }
  $statement->markDeleted(Ct::REASON_BY_USER);
  FlashMessage::add(_('Statement deleted.'), 'success');
  Util::redirectToHome();
}

$statement->enforceEditPrivileges();

if ($saveButton) {
  $statement->entityId = Request::get('entityId');
  $statement->summary = Request::get('summary');
  $statement->context = Request::get('context');
  $statement->goal = Request::get('goal');
  $statement->dateMade = Request::get('dateMade');

  $links = Link::build(
    Request::getArray('linkIds'),
    Request::getArray('linkUrls'));

  $tagIds = Request::getArray('tagIds');

  $errors = validate($statement, $links);
  if (empty($errors)) {
    $new = !$statement->id;
    $statement = $statement->maybeClone();
    $statement->save();

    if ($new) {
      Review::checkNewUser($statement);
    }
    Link::update($statement, $links);
    ObjectTag::update($statement, $tagIds);

    if ($new) {
      FlashMessage::add(_('Statement added.'), 'success');
      Util::redirect(Router::link('statement/view') . '/' . $statement->id);
    } else {
      if ($statement->status == Ct::STATUS_PENDING_EDIT) {
        FlashMessage::add(_('Your changes were placed in the review queue.'), 'success');
      } else {
        FlashMessage::add(_('Statement updated.'), 'success');
      }
      Util::redirect($referrer ?: Router::getViewLink($statement));
    }
  } else {
    Smart::assign([
      'errors' => $errors,
      'referrer' => $referrer,
      'links' =>  $links,
      'tagIds' => $tagIds,
    ]);
  }
} else {
  // first time loading the page
  Smart::assign([
    'referrer' => Util::getReferrer(),
    'links' => $statement->getLinks(),
    'tagIds' => ObjectTag::getTagIds($statement),
  ]);
}

Smart::addResources('imageModal', 'simplemde', 'linkEditor');
Smart::assign('statement', $statement);
Smart::display('statement/edit.tpl');

/*************************************************************************/

function validate($statement, $links) {
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
  foreach ($links as $l) {
    if (!$l->validUrl()) {
      $countBadUrls++;
    }
  }
  if ($countBadUrls) {
    $errors['links'][] = _('Some source URLS are invalid.');
  }

  return $errors;
}
