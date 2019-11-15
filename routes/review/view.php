<?php

$urlName = Request::get('reason');

// optional arguments
$reviewId = Request::get('reviewId');
$details = Request::get('details');
$removeButton = Request::has('removeButton');
$keepButton = Request::has('keepButton');
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

  if ($keepButton || $removeButton) {
    redirectIfComplete($r, $urlName);

    // remove existing flags
    $vote = $keepButton ? Flag::VOTE_KEEP : Flag::VOTE_REMOVE;
    Flag::delete_all_by_userId_reviewId($userId, $r->id);
    $flag = Flag::create($r->id, $details, $vote);
    $flag->save();
    FlashMessage::add(_('Your vote was recorded.'), 'success');

    $r->evaluate();
    redirectIfComplete($r, $urlName);
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

  if ($r->reason == Ct::REASON_OTHER) {
    $existingFlag = Flag::get_by_userId_reviewId($userId, $r->id);
    Smart::assign('details', $existingFlag->details ?? null);
  }
}

Smart::addResources('flag');
Smart::display('review/view.tpl');

/*************************************************************************/

/**
 * Redirects to the queue if this review is complete, so that we can offer
 * another item to review.
 */
function redirectIfComplete($review, $urlName) {
  if ($review->status != Review::STATUS_PENDING) {
    FlashMessage::add(
      _('This review is now complete. We have redirected you to the next ' .
        'review in this queue.'),
      'success');
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
