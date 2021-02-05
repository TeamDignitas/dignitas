<?php

$urlName = Request::get('reason');

// optional arguments
$reviewId = Request::get('reviewId');
$details = Request::get('details');
$removeButton = Request::has('removeButton');
$keepButton = Request::has('keepButton');
$nextButton = Request::has('nextButton');

User::enforce(User::PRIV_REVIEW);
if (Ban::exists(Ban::TYPE_REVIEW)) {
  FlashMessage::add(_('info-banned-review'));
  Util::redirectToHome();
}
$userId = User::getActiveId();

$reason = Review::getReasonFromUrlName($urlName);
if ($reason === null) {
  FlashMessage::add(_('info-no-such-queue'));
  Util::redirectToHome();
}

// load the specified review or load any available review
if ($reviewId) {

  $r = Review::get_by_id_reason($reviewId, $reason);
  if (!$r) {
    FlashMessage::add(_('info-no-such-review'));
    Util::redirectToHome();
  }
  if ($r->moderator && !User::isModerator()) {
    FlashMessage::add(_('info-moderator-review-only'));
    Util::redirectToHome();
  }

  if ($keepButton || $removeButton) {
    redirectIfComplete($r, $urlName);

    // remove existing flags
    $vote = $keepButton ? Flag::VOTE_KEEP : Flag::VOTE_REMOVE;
    Flag::delete_all_by_userId_reviewId($userId, $r->id);
    $flag = Flag::create($r->id, $details, $vote);
    $flag->save();
    Action::createFlagReviewAction($flag);

    $r->evaluate();
    redirectIfComplete($r, $urlName);

    // Only now do we generate a flash message about the vote. If the vote had
    // completed the review, we would have printed that message instead.
    FlashMessage::add(_('info-vote-saved'), 'success');
    redirectToReview($r, $urlName);
  }

  if ($nextButton) {
    // sign the log to remember that the user has processed this review
    ReviewLog::signOff($userId, $r->id);
    Util::redirect(Router::link('review/view') . '/' . $urlName);
  }

} else {

  $r = Review::load($userId, $reason);
  if ($r) {
    redirectToReview($r, $urlName);
  }
}

Smart::assign('reason', $reason);
if ($r) {
  Smart::assign('review', $r);
  Smart::addResources('flag', 'imageModal');

  if ($r->reason == Ct::REASON_PENDING_EDIT) {
    Smart::assign('objectDiff', $r->getObject()->getObjectDiff());
    Smart::addResources('history');
  }

  $existingFlag = Flag::get_by_userId_reviewId($userId, $r->id);
  Smart::assign('details', $existingFlag->details ?? null);
}

Smart::display('review/view.tpl');

/*************************************************************************/

/**
 * Redirects to the queue if this review is complete, so that we can offer
 * another item to review.
 */
function redirectIfComplete($review, $urlName) {
  if ($review->status != Review::STATUS_PENDING) {
    FlashMessage::add(_('info-vote-completes-review'), 'success');
    Util::redirect(Router::link('review/view') . '/' . $urlName);
  }
}

/**
 * Redirects to this review's URL. Useful after a save so that the POST data
 * is lost.
 */
function redirectToReview($review, $urlName) {
  $l = sprintf('%s/%s/%d', Router::link('review/view'), $urlName, $review->id);
  Util::redirect($l);
}
