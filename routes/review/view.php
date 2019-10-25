<?php

$urlName = Request::get('name');

User::enforce(User::PRIV_REVIEW);

$reason = Review::getReasonFromUrlName($urlName);
if ($reason === null) {
  FlashMessage::add(_('No review queue exists by that name.'));
  Util::redirectToHome();
}

// load an item from the review queue at random
$qi = Model::factory('Review')
  ->where('reason', $reason)
  //  ->order_by_expr('rand()')
  ->order_by_desc('id')
  ->find_one();

if ($qi) {

  // load the corresponding object
  $object = $qi->getObject();
  Smart::assign('object', $object);
}

Smart::assign([
  'reason' => $reason,
]);
Smart::display('review/view.tpl');
