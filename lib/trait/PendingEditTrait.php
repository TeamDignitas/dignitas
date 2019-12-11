<?php

/**
 * Method implementations for objects that admit pending edits.
 *
 * Classes using this trait are assumed to have the field:
 * - pendingEditId: ID, in the same table, of the record containing the new version;
 * - status: one of the Ct::STATUS_* constants; the new version should have it set to
 *   Ct::STATUS_PENDING_EDIT.
 */
trait PendingEditTrait {

  function hasPendingEdit() {
    return ($this->pendingEditId != 0);
  }

  /**
   * If the object is new (no ID) or is directly editable, simply save the
   * object and return it.
   *
   * If an edit is being suggested, then:
   *
   *   1. Clone the object and all its dependants.
   *   2. Set the pendingEditId field on the original object.
   *   3. Return the new object.
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
    $clone = $this->dbClone($refs, [ 'status' => Ct::STATUS_PENDING_EDIT ]);

    // keep the original record unchanged with the exception of pendingEditId
    $original = self::get_by_id($this->id);
    $original->pendingEditId = $clone->id;
    $original->save();

    return $clone;
  }

}
