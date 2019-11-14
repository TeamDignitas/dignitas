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

  private function changeStatus($status, $reason) {
    $this->status = $status;
    $this->reason = $reason;
    $this->statusUserId = User::getActiveId();
    $this->save();
  }

  /**
   * Marks the object as deleted.
   */
  function markDeleted($reason) {
    $this->changeStatus(Ct::STATUS_DELETED, $reason);
  }

  /**
   * Closes the object.
   */
  function close($reason) {
    $this->changeStatus(Ct::STATUS_CLOSED, $reason);
  }

  /**
   * Closes the object as a duplicate.
   */
  function closeAsDuplicate($duplicateId) {
    $this->duplicateId = $duplicateId;
    $this->close(Ct::REASON_DUPLICATE);
  }

}
