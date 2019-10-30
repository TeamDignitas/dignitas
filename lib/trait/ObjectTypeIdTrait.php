<?php

/**
 * Method implementations for objects that contain an ($objectType, $objectId)
 * reference to another object.
 */
trait ObjectTypeIdTrait {

  private $objectReference = false; // not to be confused with null

  function getObject() {
    if ($this->objectReference === false) {
      switch ($this->objectType) {
        case self::TYPE_STATEMENT:
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

  /**
   * Deletes all mentions of $o.
   *
   * @param object $o An instance of FlaggableTrait
   */
  static function deleteObject($o) {
    self::delete_all_by_objectType_objectId($o->getObjectType(), $o->id);
  }

}
