<?php

$objectType = Request::get('objectType');
$objectId = Request::get('objectId');

header('Content-Type: application/json');

try {
  $review = Review::get_by_objectType_objectId_status(
    $objectType, $objectId, Review::STATUS_PENDING);

  if ($review) {
    $flag = Flag::get_by_userId_reviewId_status(
      User::getActiveId(), $review->id, Flag::STATUS_PENDING);

    if ($flag) {
      Action::create(Action::TYPE_RETRACT_FLAG, $flag->getReview()->getObject());
      $flag->delete();
      if ($review->checkDelete()) {
        // tell the frontend to refresh the page
        http_response_code(202);
      }
    }
  }

  print json_encode(_('info-flag-deleted'));

} catch (Exception $e) {

  http_response_code(404);
  print json_encode($e->getMessage());

}
