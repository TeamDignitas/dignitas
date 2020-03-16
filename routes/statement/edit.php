<?php

$id = Request::get('id');
// prepopulated so we can quickly add statements from the entity page
$entityId = Request::get('entityId');
$saveButton = Request::has('saveButton');
$deleteButton = Request::has('deleteButton');
$referrer = Request::get('referrer');

if ($id) {
  $statement = Statement::get_by_id($id);
} else {
  $statement = Model::factory('Statement')->create();
  $statement->dateMade = Time::today();
  $statement->userId = User::getActiveId();
  $statement->entityId = $entityId;
}

if ($deleteButton) {
  if (!$statement->isDeletable()) {
    FlashMessage::add(_('info-cannot-delete-statement'));
    Util::redirectToSelf();
  }
  $statement->markDeleted(Ct::REASON_BY_USER);
  FlashMessage::add(_('info-confirm-statement-deleted'), 'success');
  Util::redirectToHome();
}

$statement->enforceEditPrivileges();

if ($saveButton) {
  $statement->entityId = $entityId;
  $statement->summary = Request::get('summary');
  $statement->context = Request::get('context');
  $statement->goal = Request::get('goal');
  $statement->dateMade = Request::get('dateMade');
  if (User::isModerator()) {
    $statement->verdict = Request::get('verdict');
  }

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
      FlashMessage::add(_('info-statement-added'), 'success');
      Util::redirect(Router::link('statement/view') . '/' . $statement->id);
    } else {
      if ($statement->status == Ct::STATUS_PENDING_EDIT) {
        FlashMessage::add(_('info-changes-queued'), 'success');
      } else {
        FlashMessage::add(_('info-statement-updated'), 'success');
      }
      Util::redirect($referrer ?: Router::getViewLink($statement));
    }
  } else {
    FlashMessage::add(_('info-validation-error'));
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

Smart::addResources('imageModal', 'easymde', 'linkEditor');
Smart::assign('statement', $statement);
Smart::display('statement/edit.tpl');

/*************************************************************************/

function validate($statement, $links) {
  $errors = [];

  if (!$statement->entityId) {
    $errors['entityId'][] = _('info-must-enter-statement-entity');
  }

  if (!$statement->summary) {
    $errors['summary'][] = _('info-must-enter-statement-summary');
  }

  if (!$statement->context) {
    $errors['context'][] = _('info-must-enter-statement-context');
  }

  if (!$statement->goal) {
    $errors['goal'][] = _('info-must-enter-statement-goal');
  }

  if (!$statement->dateMade) {
    $errors['dateMade'][] = _('info-must-enter-statement-date');
  } else if ($statement->dateMade > Time::today()) {
    $errors['dateMade'][] = _('info-date-not-future');
  }

  $countBadUrls = 0;
  foreach ($links as $l) {
    if (!$l->validUrl()) {
      $countBadUrls++;
    }
  }
  if ($countBadUrls) {
    $errors['links'][] = _('info-invalid-statement-links');
  }

  return $errors;
}
