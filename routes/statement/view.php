<?php

$id = Request::get('id');

$statement = Statement::get_by_id($id);
if (!$statement) {
  FlashMessage::add(_('info-no-such-statement'));
  Util::redirectToHome();
}

if (!$statement->isViewable()) {
  FlashMessage::add(_('info-restricted-statement'));
  Util::redirectToHome();
}

if ($statement->hasPendingEdit() && User::may(User::PRIV_REVIEW)) {
  Smart::assign([
    'pendingEditReview' => Review::getForObject($statement, Ct::REASON_PENDING_EDIT),
  ]);
}

if (User::getActive()) {
  $sideSheet = StaticResource::addCustomSections('answer-resources');
  $sideSheet = $sideSheet[0]->getContents() ?? '';
} else {
  // don't even bother loading the static resources
  $sideSheet = '';
}

Smart::addResources('flag', 'imageModal', 'easymde', 'subscribe');
Smart::assign([
  'answers' => $statement->getAnswers(),
  'newAnswer' => Answer::create($statement->id),
  'referrer' => Router::link('statement/view', true) . '/' . $statement->id,
  'statement' => $statement,
  'sideSheet' => $sideSheet,
]);
Smart::display('statement/view.tpl');
