<?php

class Flag extends BaseObject implements DatedObject {

  const REASON_SPAM = 1;
  const REASON_ABUSE = 2;
  const REASON_DUPLICATE = 3;
  const REASON_OFF_TOPIC = 4;
  const REASON_UNVERIFIABLE = 5;
  const REASON_LOW_QUALITY = 6;
  const REASON_OTHER = 7;

  const STATUS_PENDING = 1;
  const STATUS_RESOLVED = 2;

  const REVIEW_REASONS = [
    self::REASON_SPAM => Review::REASON_UNHELPFUL,
    self::REASON_ABUSE => Review::REASON_UNHELPFUL,
    self::REASON_DUPLICATE => Review::REASON_DUPLICATE,
    self::REASON_OFF_TOPIC => Review::REASON_UNHELPFUL,
    self::REASON_UNVERIFIABLE => Review::REASON_UNHELPFUL,
    self::REASON_LOW_QUALITY => Review::REASON_UNHELPFUL,
    self::REASON_OTHER => Review::REASON_OTHER,
  ];

  static function create($userId, $reviewId, $reason, $duplicateId, $details) {
    $f = Model::factory('Flag')->create();
    $f->userId = $userId;
    $f->reviewId = $reviewId;
    $f->reason = $reason;
    if ($reason == self::REASON_DUPLICATE) {
      $f->duplicateId = $duplicateId;
    } else if ($reason == self::REASON_OTHER) {
      $f->details = $details;
    }
    $f->status = self::STATUS_PENDING;
    return $f;
  }

  function getUser() {
    return User::get_by_id($this->userId);
  }

  /**
   * If this Flag has type "duplicate of", return the duplicate statement;
   * otherwise return null.
   *
   * @return Statement Statement object or null.
   */
  function getDuplicate() {
    if ($this->reason == Flag::REASON_DUPLICATE) {
      return Statement::get_by_id($this->duplicateId);
    } else {
      return null;
    }
  }

}
