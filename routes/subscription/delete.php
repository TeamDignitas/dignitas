<?php

$objectType = Request::get('objectType');
$objectId = Request::get('objectId');

header('Content-Type: application/json');

try {

  $userId = User::getActiveId();

  $subs = Subscription::get_all_by_userId_objectType_objectId_active(
    $userId, $objectType, $objectId, true);

  foreach ($subs as $sub) {
    $sub->active = false;
    $sub->save();
  }
  
  print json_encode(_('info-unsubscribed'));

} catch (Exception $e) {

  http_response_code(404);
  print json_encode($e->getMessage());

}
