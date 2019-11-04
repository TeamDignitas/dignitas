<?php

$urlName = Request::get('reason');

// optional arguments
$reviewId = Request::get('reviewId');
$nextButton = Request::has('nextButton');
$looksOkButton = Request::has('looksOkButton');

User::enforce(User::PRIV_REVIEW);

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

  if ($looksOkButton) {
    // add a "looks ok" flag and remove the existing flag, if any
    $userId = User::getActiveId();
    Flag::delete_all_by_userId_reviewId($userId, $r->id);
    $flag = Flag::create($userId, $r->id, Flag::REASON_LOOKS_OK,
                         null, null, Flag::WEIGHT_ADVISORY);
    $flag->save();

    // if there are any down votes, remove those too
    $vote = $r->getObject()->getVote();
    if ($vote && $vote->value == -1) {
      $vote->saveValue(-1); // simulate clicking the downvote button again
    }
  }

  if ($nextButton || $looksOkButton) {
    // sign the log to remember that the user has processed this review
    ReviewLog::signOff(User::getActiveId(), $r->id);
    Util::redirect(Router::link('review/view') . '/' . $urlName);
  }

} else {

  $r = Review::load(User::getActiveId(), $reason);
  if ($r) {
    $l = sprintf('%s/%s/%d', Router::link('review/view'), $urlName, $r->id);
    Util::redirect($l);
  }
}

Smart::assign('reason', $reason);
if ($r) {
  Smart::assign('review', $r);
}

Smart::addResources('flag');
Smart::display('review/view.tpl');
