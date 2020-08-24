<?php

/**
 * Discards all errors silently.
 */

$id = Request::get('id');

$not = Notification::get_by_id($id);
$user = User::getActive();

if ($not && $user && $user->id == $not->userId) {

  $subs = Subscription::get_all_by_userId_objectType_objectId_active(
    $not->userId, $not->objectType, $not->objectId, true);

  foreach ($subs as $sub) {
    $sub->active = false;
    $sub->save();
  }

}
