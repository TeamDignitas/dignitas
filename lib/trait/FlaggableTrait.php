<?php

/**
 * Method implementations for objects that can be flagged.
 */
trait FlaggableTrait {

  /**
   * Checks if the object is flagged by the active user.
   *
   * @return bool
   */
  function isFlagged() {
    $count = Model::factory('Flag')
      ->table_alias('f')
      ->join('review', ['f.reviewId', '=', 'r.id'], 'r')
      ->where('f.userId', User::getActiveId())
      ->where('f.status', Flag::STATUS_PENDING)
      ->where('r.status', Review::STATUS_PENDING)
      ->where('r.objectType', $this->getObjectType())
      ->where('r.objectId', $this->id)
      ->count();
    return ($count > 0);
  }

  /**
   * Checks if the current user may flag the object.
   *
   * @return bool
   */
  function isFlaggable() {
    return User::canFlag($this);
  }

  /**
   * If this object is active, returns null. Otherwise returns the reason of
   * the most recent resolved review.
   *
   * @return int one of the Review::REASON_* values or null.
   */
  function getReviewReason() {
    if ($this->status == self::STATUS_ACTIVE) {
      return null;
    }
    $r = Model::factory('Review')
      ->where('objectType', self::getObjectType())
      ->where('objectId', $this->id)
      ->where('status', Review::STATUS_ACCEPTED)
      ->order_by_desc('createDate')
      ->find_one();
    return $r->reason ?? null;
  }

}
