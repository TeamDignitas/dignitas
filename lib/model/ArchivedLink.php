<?php

class ArchivedLink extends Precursor {

  const STATUS_NEW = 1;       // when we initially extract the link
  const STATUS_ARCHIVED = 2;  // once the archiver has it
  const STATUS_DELETED = 3;   // when scheduled for deletion
  
  static function create(Object $obj, String $url) {
    $al = Model::factory('ArchivedLink')->create();
    $al->status = self::STATUS_NEW;
    $al->objectType = $obj->getObjectType();
    $al->objectId = $obj->id;
    $al->url = $url;
    $al->createDate = time();
    $al->save();
    return $al;
  }

  static function getForObject($obj) {
    return self::get_all_by_objectType_objectId($obj->getObjectType(), $obj->id);
  }

  function markForDeletion() {
    $this->status = self::STATUS_DELETED;
    $this->save();
  }

  /**
   * Ensures that this link won't be deleted. If needed, marks it as new. If
   * the link still exists in the archive, it will subsequently be marked as
   * STATUS_ARCHIVED without beind refetched.
   */
  function unmarkForDeletion() {
    if ($this->status == self::STATUS_DELETED) {
      $this->status = self::STATUS_NEW;
      $this->save();
    }
  }

}
