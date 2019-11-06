<?php

/**
 * Method implementations for objects that contain an ($objectType, $objectId)
 * reference to another object.
 */
trait ObjectTypeIdTrait {

  private $objectReference = false; // not to be confused with null

  function getObject() {
    if ($this->objectReference === false) {
      $this->objectReference = BaseObject::getObjectByTypeId(
        $this->objectType, $this->objectId);
      // if null, this prevents future attempts to look it up again
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
