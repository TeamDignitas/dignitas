<?php

$id = Request::get('id');
$answerId = Request::get('answerId'); // answer to be highlighted
$deleteAnswerId = Request::get('deleteAnswerId');

$statement = Statement::get_by_id($id);
if (!$statement) {
  FlashMessage::add(_('info-no-such-statement'));
  Util::redirectToHome();
}

if (!$statement->isViewable()) {
  FlashMessage::add(_('info-restricted-statement'));
  Util::redirectToHome();
}

if ($deleteAnswerId) {
  $answer = Answer::get_by_id($deleteAnswerId);
  if (!$answer) {
    FlashMessage::add(_('info-no-such-answer'));
  } else if ($answer->statementId != $statement->id) {
    FlashMessage::add(_('info-answer-belong-statement'));
  } else if (!$answer->isDeletable()) {
    FlashMessage::add(_('info-cannot-delete-answer'));
  } else {
    $answer->markDeleted(Ct::REASON_BY_USER);
    FlashMessage::add(_('info-confirm-answer-deleted'), 'success');
  }

  Util::redirect(Router::link('statement/view') . '/' . $answer->statementId);
}

if ($statement->hasPendingEdit() && User::may(User::PRIV_REVIEW)) {
  Smart::assign([
    'pendingEditReview' => Review::getForObject($statement, Ct::REASON_PENDING_EDIT),
  ]);
}

Smart::addResources('flag', 'imageModal', 'easymde');
Smart::assign([
  'answerId' => $answerId,
  'answers' => $statement->getAnswers(),
  'newAnswer' => Answer::create($statement->id),
  'referrer' => Router::link('statement/view', true) . '/' . $statement->id,
  'statement' => $statement,
]);
Smart::display('statement/view.tpl');
