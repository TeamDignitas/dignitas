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
$proposal = Request::get('proposal');

header('Content-Type: application/json');

try {

  $obj = BaseObject::getObjectByTypeId($objectType, $objectId);
  User::canFlag($obj, true);

  // also check the proposal
  if (!$obj->isValidProposal($proposal)) {
    throw new Exception(_('Invalid flag proposal.'));
  }

  $reviewReason = Flag::REVIEW_REASONS[$reason];
  $review = Review::ensure($obj, $reviewReason);
  $flag = Flag::create($review->id, $reason, $duplicateId, $details, $proposal);
  $flag->save();

  print json_encode(_('Your flag was saved.'));

} catch (Exception $e) {

  http_response_code(404);
  print json_encode($e->getMessage());

}
