<?php

$id = Request::get('id');
$statementId = Request::get('statementId');
$contents = Request::get('contents');
$saveButton = Request::has('saveButton');
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
  $answer = Model::factory('Answer')->create();
  $answer->statementId = $statementId;
  $answer->userId = User::getActiveId();
}

if ($deleteButton) {
  if (!$answer->isDeletable()) {
    Snackbar::add(_('info-cannot-delete-answer'));
  } else {
    $answer->markDeleted(Ct::REASON_BY_USER);
    $answer->subscribe();
    $answer->notify();
    Action::create(Action::TYPE_DELETE, $answer);
    Snackbar::add(_('info-confirm-answer-deleted'), 'success');
  }
  Util::redirect(Router::link('statement/view') . '/' . $answer->statementId);
}

if ($reopenButton) {
  if (!$answer->isReopenable()) {
    Snackbar::add(_('info-cannot-reopen-answer'));
  } else {
    $answer->reopen();
    $answer->subscribe();
    $answer->notify();
    Action::create(Action::TYPE_REOPEN, $answer);
    Snackbar::add(_('info-confirm-answer-reopened'), 'success');
  }

  Util::redirect(sprintf('%s/%s#a%s',
                         Router::link('statement/view'),
                         $answer->statementId,
                         $answer->id));
}

$answer->enforceEditPrivileges();

if ($saveButton) {
  $answer->contents = Request::get('contents');
  $answer->verdict = Request::get('verdict');
  $answer->sanitize();

  $errors = validate($answer);
  if (empty($errors)) {
    $originalId = $answer->id;
    $answer = $answer->maybeClone();
    $answer->save();
    $answer->subscribe();
    $answer->notify();
    Action::createUpdateAction($answer, $originalId);

    if (!$originalId) {
      Review::checkNewUser($answer);
    }
    Review::checkLateAnswer($answer);
    Review::checkRecentlyClosedDeleted($answer);

    if ($answer->status == Ct::STATUS_PENDING_EDIT) {
      Snackbar::add(_('info-changes-queued'), 'success');
    } else {
      Snackbar::add(
        $originalId ? _('info-answer-updated') : _('info-answer-posted'),
        'success');
    }
    // pass the original answer ID, not the pending edit one
    $returnTo = getReturnTo($id ?: $answer->id, $answer->statementId, $referrer);
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

function getReturnTo($answerId, $statementId, $referrer) {
  if (Str::startsWith($referrer, Router::link('review/view', true))) {

    // origin is a review view page
    return $referrer;

  } else {

    // origin is a statement view page
    return sprintf('%s/%d#a%d',
                   Router::link('statement/view'),
                   $statementId,
                   $answerId);
  }

}
