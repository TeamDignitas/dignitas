<?php

/**
 * Class that remembers users who clicked the "done" button on reviews.
 */
class ReviewLog extends Proto {

  /**
   * Creates a record for the (user, review) pair unless one exists.
   *
   * @param int $userId User ID
   * @param int $reviewId Review ID
   */
  static function signOff($userId, $reviewId) {
    $rl = self::get_by_userId_reviewId($userId, $reviewId);

    if (!$rl) {
      $rl = Model::factory('ReviewLog')->create();
      $rl->userId = $userId;
      $rl->reviewId = $reviewId;
      $rl->save();
    }

    return $r;
  }
}
