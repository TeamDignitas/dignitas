<?php

$urlName = Request::get('reason');

// optional argument
$reviewId = Request::get('reviewId');

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
} else {
  $r = Model::factory('Review')
    ->where('reason', $reason)
    //  ->order_by_expr('rand()')
    ->order_by_desc('id')
    ->find_one();
  if ($r) {
    $l = sprintf('%s/%s/%d', Router::link('review/view'), $urlName, $r->id);
    Util::redirect($l);
  }
}

Smart::assign('reason', $reason);
if ($r) {
  Smart::assign('object', $r->getObject());
}

Smart::display('review/view.tpl');
