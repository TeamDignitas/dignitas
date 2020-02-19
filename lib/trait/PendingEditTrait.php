<?php

/**
 * Method implementations for objects that admit pending edits.
 *
 * Classes using this trait are assumed to have the fields:
 * - pendingEditId: ID, in the same table, of the record containing the new revision;
 * - status: one of the Ct::STATUS_* constants; the new revision should have it set to
 *   Ct::STATUS_PENDING_EDIT.
 */
trait PendingEditTrait {

  function hasPendingEdit() {
    return ($this->pendingEditId != 0);
  }

  function getObjectDiff() {
    if (!$this->hasPendingEdit()) {
      return null;
    }

    $pending = static::get_by_id($this->pendingEditId);
    $objectDiffs = ObjectDiff::loadFor($pending);

    if (count($objectDiffs) != 1) {
      Log::error('Pending edit objects should have exactly two revisions.');
      return null;
    }
    return $objectDiffs[0];
  }

  /**
   * Checks whether this object is directly editable by the active user.
   * Should handle core logic only and leave the pending edit analysis to isEditable().
   * Should throw exceptions with informative messages.
   *
   * @return nothing
   */
  abstract protected function isEditableCore();

  /**
   * Checks whether this object is directly editable by the active user.
   * This is a wrapper around isEditableCore that takes pending edits into accounts.
   *
   * @param $throw Throw an exception with an explanatory message instead of returning false.
   * @return boolean
   */
  function isEditable($throw = false) {
    try {
      if ($this->status == Ct::STATUS_PENDING_EDIT) {
        throw new Exception(_('info-item-is-pending-edit'));
      }

      if ($this->pendingEditId) {
        throw new Exception(
          _('info-item-has-pending-edit'));
      }

      $u = User::getActive();
      if ($u && $u->getNumPendingEdits() >= Config::MAX_PENDING_EDITS) {
        throw new Exception(sprintf(_('info-pending-edit-limit-%d'), Config::MAX_PENDING_EDITS));
      }

      $this->isEditableCore();

      return true;

    } catch (Exception $e) {
      if ($throw) {
        throw $e;
      }

      return false;
    }
  }

  /**
   * Checks if the active user can suggest changes to this object.
   *
   * @return boolean
   */
  function acceptsSuggestions() {
    $u = User::getActive();
    return
      // must be logged in
      $u &&

      // object is not itself a pending edit
      ($this->status != Ct::STATUS_PENDING_EDIT) &&

      // object does not already have pending edits
      !$this->pendingEditId &&

       // must not exceed pending edit limit
      ($u->getNumPendingEdits() < Config::MAX_PENDING_EDITS);

  }

  /**
   * Checks if the active user should be allowed to view this object's edit
   * page, whether to edit it or to suggest changes. If the user should not be
   * allowed to view the edit page, tries to set an informative error message
   * and redirects back to the referrer.
   */
  function enforceEditPrivileges() {
    if ($this->acceptsSuggestions()) {
      return;
    }

    try {
      $this->isEditable(true);
    } catch (Exception $e) {
      FlashMessage::add($e->getMessage());
      Util::redirect(Util::getReferrer());
    }
  }

  /**
   * If the object is new (no ID) or is directly editable, simply return it.
   *
   * If an edit is being suggested, then:
   *
   *   1. Clone and save the original object and all its dependants.
   *   2. Populate the new field values on the clone, but don't save it yet.
   *   3. Set the pendingEditId field on the original object.
   *   4. Start a review for the pending edit.
   *   5. Increment the active user's pending edit count.
   *   6. Return the clone.
   *
   * We do things this way so that the clone has two revisions. The first one
   * and its dependants are identical to the original object. The second one
   * captures the changes in this edit. This makes the diff easy to compute.
   *
   * @return PendingEditTrait The same object or its clone
   */
  function maybeClone() {
    if (!$this->id || $this->isEditable()) {
      return $this;
    }

    // 1
    $original = self::get_by_id($this->id);
    $clone = $original->deepClone(null, [ 'status' => Ct::STATUS_PENDING_EDIT ]);

    // change the request ID so that edits applied on top of the clone can be
    // tracked separately
    DB::pickRequestId();

    // 2
    $clone->copyFrom($this);

    // 3
    $original->pendingEditId = $clone->id;
    $original->save();

    // 4
    Review::ensure($original, Ct::REASON_PENDING_EDIT);

    // 5
    User::getActive()->incrementPendingEdits();

    // 6
    return $clone;
  }

  /**
   * Opposite of deepClone(). Copies fields from $other. Deletes, inserts or
   * updates dependants as dictated by CloneMap records.
   */
  abstract protected function deepMerge($other);

  /**
   * Merges a list of $other's dependants into $this. Called while merging a
   * pending edit.
   *
   * @param PendingEditTrait $other Pending edit of $this
   * @param Proto[] $origDeps Array of original dependants
   * @param Proto[] $clonedDeps Array of cloned dependants
   * @param string $fkField Field whose value should change from $other->id to
   * $this->id
   */
  function mergeDependants($other, $origDeps, $clonedDeps, $fkField) {
    $seenIdsMap = [];
    foreach ($clonedDeps as $c) {
      // Look for a corresponding original dependant
      $orig = CloneMap::getOriginal($other, $c);
      if ($orig) {
        $orig->copyFrom($c);
      } else {
        $orig = $c->parisClone();
      }
      $orig->$fkField = $this->id;
      $orig->save($other->modUserId);
      $seenIdsMap[$orig->id] = true;
    }

    // delete original dependants that we have not seen among $clonedDeps
    foreach ($origDeps as $o) {
      if (!isset($seenIdsMap[$o->id])) {
        $o->delete();
      }
    }
  }

  /**
   * Processes the pending edit associated with this object.
   *
   * @param bool $accept If true, incorporates the changes, otherwise discards them.
   */
  function processPendingEdit(bool $accept) {
    $pending = static::get_by_id($this->pendingEditId);
    if ($pending) {
      $u = User::get_by_id($pending->modUserId);
      $u->decrementPendingEdits();
      if ($accept) {
        // this will also clear the $this->pendingEditId field and save $this
        $this->deepMerge($pending);
        $u->grantReputation(Config::REP_SUGGESTED_EDIT);
      } else {
        $this->pendingEditId = 0;
        $this->save();
      }
      CloneMap::deleteRoot($pending);
      $pending->delete();
    }
  }

}
