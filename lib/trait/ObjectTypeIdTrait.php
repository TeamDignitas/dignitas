<?php

/**
 * Method implementations for objects that contain an ($objectType, $objectId)
 * reference to another object. Classes that use this trait should also
 * implement ObjectTypes to gain access to its constants.
 */
trait ObjectTypeIdTrait {

  private $objectReference = false; // not to be confused with null

  function getObject() {
    if ($this->objectReference === false) {
      switch ($this->objectType) {
        case ObjectTypes::TYPE_STATEMENT:
          $this->objectReference = Statement::get_by_id($this->objectId);
          break;
        case ObjectTypes::TYPE_ANSWER:
          $this->objectReference = Answer::get_by_id($this->objectId);
          break;
        default:
          $this->objectReference = null; // prevents future attempts to look it up again
      }
    }
    return $this->objectReference;
  }

  /**
   * Deletes all mentions of $o.
   *
   * @param $o An instance of FlaggableTrait
   */
  static function deleteObject($o) {
    self::delete_all_by_objectType_objectId($o->getFlagType(), $o->id);
  }

}
