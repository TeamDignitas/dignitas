<?php

class Flag extends BaseObject implements DatedObject {
  use ObjectTypeIdTrait;

  const REASON_SPAM = 1;
  const REASON_ABUSE = 2;
  const REASON_DUPLICATE = 3;
  const REASON_OFF_TOPIC = 4;
  const REASON_LOW_QUALITY = 5;
  const REASON_OTHER = 6;

  const STATUS_PENDING = 0;
  const STATUS_RESOLVED = 1;

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

}
