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
        throw new Exception(_('This item is itself a pending edit.'));
      }

      if ($this->pendingEditId) {
        throw new Exception(
          _('This item already has a pending edit; please wait for it to be reviewed.'));
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
    return
      (User::getActive() != null) &&                // must be logged in
      ($this->status != Ct::STATUS_PENDING_EDIT) && // object is not itself a pending edit
      !$this->pendingEditId;                        // object does not already have pending edits
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
   * If the object is new (no ID) or is directly editable, simply save the
   * object and return it.
   *
   * If an edit is being suggested, then:
   *
   *   1. Clone the object and all its dependants.
   *   2. Set the pendingEditId field on the original object.
   *   3. Start a review for the pending edit.
   *   4. Return the new object.
   *
   * @param Map $refs Will collect the old ID => new ID map by object type
   * @return PendingEditTrait The same object or its clone
   */
  function saveOrClone(&$refs) {
    if (!$this->id || $this->isEditable()) {
      $this->save();
      return $this;
    }

    // clone the object with its dependants and mark it as a pending edit
    $clone = $this->deepClone($refs, [ 'status' => Ct::STATUS_PENDING_EDIT ]);

    // keep the original record unchanged with the exception of pendingEditId
    $original = self::get_by_id($this->id);
    $original->pendingEditId = $clone->id;
    $original->save();

    // start a review
    Review::ensure($original, Ct::REASON_PENDING_EDIT);

    return $clone;
  }

  /**
   * Opposite of deepClone(). Copies fields from $other. Deletes own
   * dependants. Transfers dependants from $other.
   */
  abstract protected function deepMerge($other);

  /**
   * Processes the pending edit associated with this object.
   *
   * @param bool $accept If true, incorporates the changes, otherwise discards them.
   */
  function processPendingEdit(bool $accept) {
    $pending = static::get_by_id($this->pendingEditId);
    if ($pending) {
      if ($accept) {
        // this will also clear $this->pendingEditId field and save $this
        $this->deepMerge($pending);
      } else {
        $this->pendingEditId = 0;
        $this->save();
      }
      $pending->delete();
    }
  }

}
