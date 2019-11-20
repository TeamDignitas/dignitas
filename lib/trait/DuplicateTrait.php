<?php

/**
 * Method implementations for objects that have duplicates. Classes
 * implementing this trait should have status and duplicateId fields.
 */
trait DuplicateTrait {

  /**
   * If this Object is closed as a duplicate, returns the duplicate object;
   * otherwise returns null.
   *
   * @return Object Object of the same type as $this or null.
   */
  function getDuplicate() {
    return (($this->status == Ct::STATUS_CLOSED) && $this->duplicateId)
      ? static::get_by_id($this->duplicateId)
      : null;
  }

}
