<?php

/**
 * Method implementations for objects that contain an ($objectType, $objectId)
 * reference to another object. Classes that use this trait should also
 * implement ObjectTypes to gain access to its constants.
 */
trait ObjectTypeIdTrait {

  static $TYPE_STATEMENT = 1;
  static $TYPE_ANSWER = 2;

  private $objectReference = false; // not to be confused with null

  function getObject() {
    if ($this->objectReference === false) {
      switch ($this->objectType) {
        case self::$TYPE_STATEMENT:
          $this->objectReference = Statement::get_by_id($this->objectId);
          break;
        case self::TYPE_ANSWER:
          $this->objectReference = Answer::get_by_id($this->objectId);
          break;
        default:
          $this->objectReference = null; // prevents future attempts to look it up again
      }
    }
    return $this->objectReference;
  }

}
