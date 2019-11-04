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
$weight = Request::get('weight');

header('Content-Type: application/json');

try {

  User::canFlag($objectType, $objectId, true);

  // also check the weight
  $allowedWeights = User::may(User::PRIV_CLOSE_REOPEN_VOTE)
    ? (($objectType == BaseObject::TYPE_STATEMENT)
       ? [ Flag::WEIGHT_ADVISORY, Flag::WEIGHT_CLOSE, Flag::WEIGHT_DELETE ]
       : [ Flag::WEIGHT_ADVISORY, Flag::WEIGHT_DELETE ])
    : [ Flag::WEIGHT_ADVISORY ];
  if (!in_array($weight, $allowedWeights)) {
    throw new Exception(_('Invalid flag recommendation.'));
  }

  $userId = User::getActiveId();
  $reviewReason = Flag::REVIEW_REASONS[$reason];
  $review = Review::ensure($objectType, $objectId, $reviewReason);
  $flag = Flag::create($userId, $review->id, $reason, $duplicateId, $details, $weight);
  $flag->save();

  print json_encode(_('Your flag was saved.'));

} catch (Exception $e) {

  http_response_code(404);
  print json_encode($e->getMessage());

}
