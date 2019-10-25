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
      $flag->delete();
      $review->checkDelete();
    }
  }

  print json_encode(_('Your flag was deleted.'));

} catch (Exception $e) {

  http_response_code(404);
  print json_encode($e->getMessage());

}
