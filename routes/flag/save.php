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
  $reviewReason = Flag::REVIEW_REASONS[$reason];
  $review = Review::ensure($objectType, $objectId, $reviewReason);
  $flag = Flag::create($userId, $review->id, $reason, $duplicateId, $details);
  $flag->save();

  print json_encode(_('Your flag was saved.'));

} catch (Exception $e) {

  http_response_code(404);
  print json_encode($e->getMessage());

}
