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
    return User::canFlag($this) &&
      ($this->status != Ct::STATUS_DELETED);
  }

  /**
   * Checks if the object requires a moderator-level review. Users will
   * override this.
   **/
  function requiresModeratorReview()  {
    return false;
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
      case Ct::STATUS_ACTIVE: return _('status-diff-active');
      case Ct::STATUS_CLOSED: return $this->duplicateId
        ? _('status-diff-duplicate')
        : _('status-diff-closed');
      case Ct::STATUS_DELETED: return _('status-diff-deleted');
      case Ct::STATUS_PENDING_EDIT: return _('status-diff-pending-edit');
    }
  }

  /**
   * Return the most recent timestamp when $this stopped being active.
   *
   * @return int A timestamp or null if $this is still active or if $this is a
   * pending edit.
   */
  function getDeletionClosureTimestamp() {
    if (!in_array($this->status, [ Ct::STATUS_CLOSED, Ct::STATUS_DELETED ])) {
      return null;
    }

    // load the last active revision
    $class = $this->getRevisionClass();
    $lastActive = Model::factory($class)
      ->where('id', $this->id)
      ->where('status', Ct::STATUS_ACTIVE)
      ->order_by_desc('revisionId')
      ->find_one();

    // load the next revision
    $firstInactive = Model::factory($class)
      ->where('id', $this->id)
      ->where_gt('revisionId', $lastActive->revisionId)
      ->order_by_asc('revisionId')
      ->find_one();

    return $firstInactive->modDate;
  }

  /**
   * When $dir is true (the object is being deleted), give back reputation to
   * people who downvoted this object. When $dir is false (the object is being
   * reopened), take away reputation from people who downvoted this object.
   *
   * @param bool $dir true if the object is being deleted, false if reopened.
   */
  function undoDownvoteRep($dir) {
    $type = $this->getObjectType();
    $change = Vote::VOTER_REP_COST[$type]; // could be 0, e.g. for comments

    if ($dir) {
      $change = -$change;
    }

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
   * Applies consequences of marking an object as deleted. Users will override
   * this. Called before saving the deleted object.
   */
  function markDeletedEffects() {
  }

  /**
   * Marks the object as deleted. Resolves all pending reviews for the object
   * as STATUS_OBJECT_GONE.
   */
  function markDeleted($reason) {
    // switch from REASON_BY_USER to REASON_BY_OWNER if applicable
    if ($reason == Ct::REASON_BY_USER &&
        ($this->userId == User::getActiveId())) {
      $reason = Ct::REASON_BY_OWNER;
    }
    $this->markDeletedEffects();
    $this->changeStatus(Ct::STATUS_DELETED, $reason);
    $this->undoDownvoteRep(true);

    $reviews = Review::get_all_by_objectType_objectId_status(
      $this->getObjectType(), $this->id, Review::STATUS_PENDING);
    foreach ($reviews as $r) {
      $r->resolveUncommon(Review::STATUS_OBJECT_GONE);
    }
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
   * Marks the object as active (reopened).
   */
  function reopen() {
    $this->changeStatus(Ct::STATUS_ACTIVE, CT::REASON_REOPEN);
    $this->undoDownvoteRep(false);
  }

}
