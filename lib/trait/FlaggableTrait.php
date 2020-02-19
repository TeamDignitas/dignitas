<?php

/**
 * Method implementations for objects that can be flagged.
 *
 * Classes using this trait are assumed to have the fields:
 * - status: one of the Ct::STATUS_* constants;
 * - statusUserId: user who last changed the status;
 * - reason: reason for last changing the status;
 * - userId:user who created this object.
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

  function getStatusName() {
    switch ($this->status) {
      case Ct::STATUS_ACTIVE: return _('active');
      case Ct::STATUS_CLOSED: return $this->duplicateId ? _('duplicate') : _('closed');
      case Ct::STATUS_DELETED: return _('deleted');
      case Ct::STATUS_PENDING_EDIT: return _('pending edit');
    }
  }

  /**
   * Give back reputation to people who downvoted this object. Applicable when
   * the object is deleted.
   */
  function undoDownvoteRep() {
    $type = $this->getObjectType();
    $change = -Vote::VOTER_REP_COST[$type]; // could be 0, e.g. for comments

    if ($change) {
      $votes = Vote::get_all_by_objectType_objectId_value($type, $this->id, -1);
      foreach ($votes as $v) {
        $u = User::get_by_id($v->userId);
        $u->grantReputation($change);
      }
    }
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
    // switch from REASON_BY_USER to REASON_BY_OWNER if applicable
    if ($reason == Ct::REASON_BY_USER &&
        ($this->userId == User::getActiveId())) {
      $reason = Ct::REASON_BY_OWNER;
    }
    $this->changeStatus(Ct::STATUS_DELETED, $reason);
    $this->undoDownvoteRep();
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
