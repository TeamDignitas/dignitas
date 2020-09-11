<?php

/**
 * For illegal operations, we return an error code of 404 and print the
 * JSON-encoded error message.
 **/

$objectType = Request::get('objectType');
$objectId = Request::get('objectId');

header('Content-Type: application/json');

try {

  $userId = User::getActiveId();
  if (!$userId) {
    throw new Exception(_('info-must-log-in'));
  }

  $obj = Proto::getObjectByTypeId($objectType, $objectId);
  $mask = Subscription::TYPE_ALL ^ Subscription::TYPE_VOTE; // no votes

  // subscribe() won't work if an inactive subscription exists, so delete it first
  Subscription::delete_all_by_userId_objectType_objectId(
    $userId, $objectType, $objectId);
  Subscription::subscribe($obj, $userId, $mask);

  print json_encode(_('info-subscribed'));

} catch (Exception $e) {

  http_response_code(404);
  print json_encode($e->getMessage());

}
