<?php

class Flag extends BaseObject implements DatedObject {

  const TYPE_STATEMENT = 1;
  const TYPE_ANSWER = 2;

  const REASON_SPAM = 1;
  const REASON_ABUSE = 2;
  const REASON_DUPLICATE = 3;
  const REASON_OFF_TOPIC = 4;
  const REASON_LOW_QUALITY = 5;
  const REASON_OTHER = 6;

  const STATUS_PENDING = 0;
  const STATUS_RESOLVED = 1;

  private $object = false; // not to be confused with null

  static function create($objectType, $objectId, $userId = null, $reason = null,
                         $duplicateId = null, $details = null) {
    $f = Model::factory('Flag')->create();
    $f->userId = $userId;
    $f->objectType = $objectType;
    $f->objectId = $objectId;
    $f->reason = $reason;
    if ($reason == self::REASON_DUPLICATE) {
      $f->duplicateId = $duplicateId;
    } else if ($reason == self::REASON_OTHER) {
      $f->details = $details;
    }
    return $f;
  }

  function getObject() {
    if ($this->object === false) {
      switch ($this->objectType) {
        case self::TYPE_STATEMENT:
          $this->object = Statement::get_by_id($this->objectId);
          break;
        case self::TYPE_ANSWER:
          $this->object = Answer::get_by_id($this->objectId);
          break;
        default:
          $this->object = null; // prevents future attempts to look it up again
      }
    }
    return $this->object;
  }

}
