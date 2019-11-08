<?php

$urlName = Request::get('reason');

// optional arguments
$reviewId = Request::get('reviewId');
$details = Request::get('details');
$yeaButton = Request::has('yeaButton');
$nayButton = Request::has('nayButton');
$nextButton = Request::has('nextButton');

User::enforce(User::PRIV_REVIEW);
$userId = User::getActiveId();

$reason = Review::getReasonFromUrlName($urlName);
if ($reason === null) {
  FlashMessage::add(_('No review queue exists by that name.'));
  Util::redirectToHome();
}

// load the specified review or load any available review
if ($reviewId) {

  $r = Review::get_by_id_reason($reviewId, $reason);
  if (!$r) {
    FlashMessage::add(_('No review exists with the given ID.'));
    Util::redirectToHome();
  }

  if ($yeaButton || $nayButton) {
    // remove existing flags
    $vote = $yeaButton ? Flag::VOTE_YEA : Flag::VOTE_NAY;
    Flag::delete_all_by_userId_reviewId($userId, $r->id);
    $flag = Flag::create($r->id, $details, $vote);
    $flag->save();
    $r->evaluate();
    FlashMessage::add(_('Your vote was recorded.'), 'success');
  }

  if ($nextButton) {
    // sign the log to remember that the user has processed this review
    ReviewLog::signOff($userId, $r->id);
    Util::redirect(Router::link('review/view') . '/' . $urlName);
  }

} else {

  $r = Review::load($userId, $reason);
  if ($r) {
    $l = sprintf('%s/%s/%d', Router::link('review/view'), $urlName, $r->id);
    Util::redirect($l);
  }
}

Smart::assign('reason', $reason);
if ($r) {
  Smart::assign('review', $r);

  if ($r->reason == Review::REASON_OTHER) {
    $existingFlag = Flag::get_by_userId_reviewId($userId, $r->id);
    Smart::assign('details', $existingFlag->details ?? null);
  }
}

Smart::addResources('flag');
Smart::display('review/view.tpl');
