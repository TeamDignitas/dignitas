<?php

$urlName = Request::get('reason');

// optional argument
$reviewId = Request::get('reviewId');
$doneButton = Request::has('doneButton');

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

  if ($doneButton) {
    // submit a flag to remember that the user has processed this review
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
