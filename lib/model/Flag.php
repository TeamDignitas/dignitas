<?php

class Flag extends BaseObject implements DatedObject {

  const REASON_SPAM = 1;
  const REASON_ABUSE = 2;
  const REASON_DUPLICATE = 3;
  const REASON_OFF_TOPIC = 4;
  const REASON_UNVERIFIABLE = 5;
  const REASON_LOW_QUALITY = 6;
  const REASON_OTHER = 7;
  const REASON_LOOKS_OK = 8;

  // Action proposed by this flag. Unprivileged users always raise PROP_NOTHING
  // flags. Answers can never have close flags.
  const PROP_NOTHING = 0;
  const PROP_CLOSE = 1;
  const PROP_DELETE = 2;
  const PROP_LEAVE = 3;

  // Whether the flag was raised by a privileged user.
  const WEIGHT_ADVISORY = 0;
  const WEIGHT_EXECUTIVE = 1;

  const REVIEW_REASONS = [
    self::REASON_SPAM => Review::REASON_UNHELPFUL,
    self::REASON_ABUSE => Review::REASON_UNHELPFUL,
    self::REASON_DUPLICATE => Review::REASON_DUPLICATE,
    self::REASON_OFF_TOPIC => Review::REASON_UNHELPFUL,
    self::REASON_UNVERIFIABLE => Review::REASON_UNHELPFUL,
    self::REASON_LOW_QUALITY => Review::REASON_UNHELPFUL,
    self::REASON_OTHER => Review::REASON_OTHER,
  ];

  static function create($reviewId, $reason, $duplicateId, $details, $proposal) {
    $f = Model::factory('Flag')->create();
    $f->userId = User::getActiveId();
    $f->reviewId = $reviewId;
    $f->reason = $reason;
    if ($reason == self::REASON_DUPLICATE) {
      $f->duplicateId = $duplicateId;
    } else if ($reason == self::REASON_OTHER) {
      $f->details = $details;
    }
    $f->proposal = $proposal;
    $f->weight = User::may(User::PRIV_CLOSE_REOPEN_VOTE)
      ? self::WEIGHT_EXECUTIVE
      : self::WEIGHT_ADVISORY;
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
