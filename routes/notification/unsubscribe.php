<?php

/**
 * Discards all errors silently.
 */

$id = Request::get('id');

$not = Notification::get_by_id($id);
$user = User::getActive();

if ($not && $user && $user->id == $not->userId) {

  $subs = Model::factory('Subscription')
    ->where('userId', $not->userId)
    ->where('objectType', $not->objectType)
    ->where_in('objectId', [ 0, $not->objectId ])
    ->where('active', true)
    ->find_many();

  foreach ($subs as $sub) {
    $sub->active = false;
    $sub->save();
  }

}
