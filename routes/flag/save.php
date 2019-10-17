<?php

/**
 * For illegal operations, we return an error code of 404 and print the
 * JSON-encoded error message.
 **/

$objectType = Request::get('objectType');
$objectId = Request::get('objectId');
$reason = Request::get('reason');
$duplicateId = Request::get('duplicateId');
$details = Request::get('details');

header('Content-Type: application/json');

try {

  User::canFlag($objectType, $objectId, true);
  $userId = User::getActiveId();
  $flag = Flag::create($objectType, $objectId, $userId, $reason, $duplicateId, $details);
  $flag->save();

  print json_encode(_('Your flag was saved.'));

} catch (Exception $e) {

  http_response_code(404);
  print json_encode($e->getMessage());

}
