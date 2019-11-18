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
   * Returns the flags of the review that decided the object's current status.
   */
  function getReviewFlags() {
    $flags = Model::factory('Flag')
      ->table_alias('f')
      ->select('f.*')
      ->join('review', ['f.reviewId', '=', 'r.id'], 'r')
      ->where('r.objectType', $this->getObjectType())
      ->where('r.objectId', $this->id)
      ->where('r.reason', $this->reason)
      ->where('r.status', Review::STATUS_REMOVE);
    if ($this->reason == Ct::REASON_DUPLICATE) {
      $flags = $flags
        ->where('r.duplicateId', $this->duplicateId);
    }
    return $flags
      ->order_by_desc('f.createDate')
      ->find_many();
  }

  function getStatusUser() {
    return User::get_by_id($this->statusUserId);
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

  /**
   * Flaggable objects should be marked as deleted, never actually deleted.
   */
  function delete() {
    $class = get_class($this);
    throw new Exception(
      "Objects of class '{$class}' should never be deleted at the DB level.");
  }
}
