<?php

$id = Request::get('id');
$statementId = Request::get('statementId');
$contents = Request::get('contents');
$saveButton = Request::has('saveButton');
$saveDraftButton = Request::has('saveDraftButton');
$deleteButton = Request::has('deleteButton');
$reopenButton = Request::has('reopenButton');
$referrer = Request::get('referrer');

if ($id) {
  $answer = Answer::get_by_id($id);
  if (!$answer) {
    Snackbar::add(_('info-no-such-answer'));
    Util::redirectToHome();
  }
} else {
  $answer = Answer::create($statementId);
  $answer->userId = User::getActiveId();
}

$statement = $answer->getStatement();

if ($deleteButton) {
  if (!$answer->isDeletable()) {
    Snackbar::add(_('info-cannot-delete-answer'));
  } else if ($answer->status == Ct::STATUS_DRAFT) {
    $answer->purge();
    Snackbar::add(_('info-confirm-answer-draft-deleted'));
  } else {
    $answer->markDeleted(Ct::REASON_BY_USER);
    $answer->subscribe();
    $answer->notify();
    Action::create(Action::TYPE_DELETE, $answer);
    Snackbar::add(_('info-confirm-answer-deleted'));
  }
  Util::redirect($statement->getViewUrl());
}

if ($reopenButton) {
  if (!$answer->isReopenable()) {
    Snackbar::add(_('info-cannot-reopen-answer'));
  } else {
    $answer->reopen();
    $answer->subscribe();
    $answer->notify();
    Action::create(Action::TYPE_REOPEN, $answer);
    Snackbar::add(_('info-confirm-answer-reopened'));
  }

  Util::redirect($statement->getViewUrl() . '#a' . $answer->id);
}

$answer->enforceEditPrivileges();

if ($saveButton || $saveDraftButton) {
  $publicized = false; // true if the answer was a draft and is now publicized

  $answer->contents = Request::get('contents');
  $answer->verdict = Request::get('verdict');
  if ($saveButton && ($answer->status == Ct::STATUS_DRAFT)) {
    $publicized = true;
    $answer->status = Ct::STATUS_ACTIVE;
    $answer->createDate = time(); // make it look like $answer was just created
  }
  $answer->sanitize();

  $errors = validate($answer);
  if (empty($errors)) {
    // This will trigger the new user review when the answer is published, not
    // while it is a draft. Also, the action logged will be a create, not an
    // update.
    $originalId = $publicized ? null : $answer->id;
    $answer = $answer->maybeClone();
    $answer->save();

    if ($answer->status != Ct::STATUS_DRAFT) {
      if ($publicized) {
        $answer->deleteDraftRevisions();
      }
      $answer->subscribe();
      $answer->notify();
      Action::createUpdateAction($answer, $originalId);

      if (!$originalId) {
        Review::checkNewUser($answer);
      }
      Review::checkLateAnswer($answer);
      Review::checkRecentlyClosedDeleted($answer);
    }

    if ($answer->status == Ct::STATUS_PENDING_EDIT) {
      Snackbar::add(_('info-changes-queued'));
    } else {
      Snackbar::add(
        $originalId ? _('info-answer-updated') : _('info-answer-posted'),
        'success');
    }
    // pass the original answer ID, not the pending edit one
    $returnTo = getReturnTo($id ?: $answer->id, $statement, $referrer);
    Util::redirect($returnTo);
  } else {
    Smart::assign([
      'errors' => $errors,
      'referrer' => $referrer,
    ]);
  }
} else {
  // first time loading the page
  Smart::assign('referrer', Util::getReferrer());
}

$sideSheet = StaticResource::addCustomSections('answer-resources');
$sideSheet = $sideSheet[0]->getContents() ?? '';

Smart::addResources('answerResources', 'easymde', 'imageModal');
Smart::assign([
  'answer' => $answer,
  'sideSheet' => $sideSheet,
]);
Smart::display('answer/edit.tpl');

/*************************************************************************/

// When validation fails, we display the answer edit page, although we
// could have come from the inline editor on the statement view page.
// This is a reasonable price to pay for now since validation is (almost)
// guaranteed to succeed.
function validate($answer) {
  $errors = [];

  if (!$answer->contents) {
    $errors['contents'][] = _('info-empty-answer');
  }

  return $errors;
}

function getReturnTo($answerId, $statement, $referrer) {
  if (Str::startsWith($referrer, Router::link('review/view', true))) {

    // origin is a review view page
    return $referrer;

  } else {

    // origin is a statement view page
    return $statement->getViewUrl() . '#a' . $answerId;
  }

}
