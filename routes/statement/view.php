<?php

$id = Request::get('id');

$statement = Statement::get_by_id($id);
if (!$statement) {
  Snackbar::add(_('info-no-such-statement'));
  Util::redirectToHome();
}

if (!$statement->isViewable()) {
  Snackbar::add(_('info-restricted-statement'));
  Util::redirectToHome();
}

if ($statement->hasPendingEdit() && User::may(User::PRIV_REVIEW)) {
  Smart::assign([
    'pendingEditReview' => Review::getForObject($statement, Ct::REASON_PENDING_EDIT),
  ]);
}

if (User::getActive()) {
  Smart::addResources('answerResources');
  $sideSheet = StaticResource::addCustomSections('answer-resources');
  $sideSheet = $sideSheet[0]->getContents() ?? '';
} else {
  // don't even bother loading the static resources
  $sideSheet = '';
}

Smart::addResources('imageModal');
if (User::getActive()) {
  Smart::addResources('easymde', 'flag', 'subscribe');
}
Smart::assign([
  'answers' => $statement->getAnswers(),
  'newAnswer' => Answer::create($statement->id),
  'referrer' => $statement->getViewUrl(true),
  'statement' => $statement,
  'sideSheet' => $sideSheet,
]);
Smart::display('statement/view.tpl');
