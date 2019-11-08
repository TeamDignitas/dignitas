<?php

User::enforce(1); /* just ensure user is logged in */

if (User::may(User::PRIV_REVIEW)) {
  // get reasons of pending reviews
  $reasons = Model::factory('Review')
    ->select('reason')
    ->distinct()
    ->where('status', Review::STATUS_PENDING)
    ->order_by_asc('reason')
    ->find_many();
  $reasons = Util::objectProperty($reasons, 'reason');
  Smart::assign('activeReviewReasons', $reasons);
}

Smart::display('aggregate/dashboard.tpl');
