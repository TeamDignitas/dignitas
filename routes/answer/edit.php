<?php

$id = Request::get('id');
$statementId = Request::get('statementId');
$contents = Request::get('contents');
$saveButton = Request::has('saveButton');
$referrer = Request::get('referrer');

if ($id) {
  $answer = Answer::get_by_id($id);
} else {
  $answer = Model::factory('Answer')->create();
  $answer->statementId = $statementId;
  $answer->userId = User::getActiveId();
}

$answer->enforceEditPrivileges();

if ($saveButton) {
  $answer->contents = Request::get('contents');
  $answer->sanitize();

  $errors = validate($answer);
  if (empty($errors)) {
    $new = !$answer->id;
    $answer = $answer->maybeClone();
    $answer->save();

    if ($new) {
      Review::checkNewUser($answer);
    }
    Review::checkLateAnswer($answer);

    if ($answer->status == Ct::STATUS_PENDING_EDIT) {
      FlashMessage::add(_('info-changes-queued'), 'success');
    } else {
      FlashMessage::add(
        $new ? _('info-answer-posted') : _('info-answer-updated'),
        'success');
    }
    // pass the original answer ID, not the pending edit one
    $returnTo = getReturnTo($id, $answer->statementId, $referrer);
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

Smart::addResources('imageModal', 'simplemde');
Smart::assign([
  'answer' => $answer,
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
    return sprintf('%s/%d/%d',
                   Router::link('statement/view'),
                   $statementId,
                   $answerId);
  }

}
