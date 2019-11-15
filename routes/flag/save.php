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

  $obj = BaseObject::getObjectByTypeId($objectType, $objectId);
  User::canFlag($obj, true);

  if ($duplicateId == $objectId) {
    throw new Exception(_('Cannot flag statement as a duplicate of itself.'));
  }

  $review = Review::ensure($obj, $reason, $duplicateId);
  $flag = Flag::create($review->id, $details, Flag::VOTE_REMOVE);
  $flag->save();
  $review->evaluate();

  print json_encode(_('Your flag was saved.'));

} catch (Exception $e) {

  http_response_code(404);
  print json_encode($e->getMessage());

}
