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

  User::canFlag($objectType, $objectId, true);

  // also check the proposal
  $allowedProps = User::may(User::PRIV_CLOSE_REOPEN_VOTE)
    ? (($objectType == BaseObject::TYPE_STATEMENT)
       ? [ Flag::PROP_NOTHING, Flag::PROP_CLOSE, Flag::PROP_DELETE ]
       : [ Flag::PROP_NOTHING, Flag::PROP_DELETE ])
    : [ Flag::PROP_NOTHING ];
  if (!in_array($proposal, $allowedProps)) {
    throw new Exception(_('Invalid flag proposal.'));
  }

  $reviewReason = Flag::REVIEW_REASONS[$reason];
  $review = Review::ensure($objectType, $objectId, $reviewReason);
  $flag = Flag::create($review->id, $reason, $duplicateId, $details, $proposal);
  $flag->save();

  print json_encode(_('Your flag was saved.'));

} catch (Exception $e) {

  http_response_code(404);
  print json_encode($e->getMessage());

}
