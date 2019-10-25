<?php

/**
 * Class that handles an object review from start to resolution.
 */
class Review extends BaseObject implements DatedObject {
  use ObjectTypeIdTrait;

  // What is the current nature of this review?
  const REASON_UNHELPFUL = 1;
  const REASON_DUPLICATE = 2;
  const REASON_FIRST_POST = 3;
  const REASON_LATE_ANSWER = 4;
  const REASON_CLOSE = 5;
  const REASON_REOPEN = 6;
  const REASON_OTHER = 7;

  const REASONS = [
    self::REASON_UNHELPFUL,
    self::REASON_DUPLICATE,
    self::REASON_FIRST_POST,
    self::REASON_LATE_ANSWER,
    self::REASON_CLOSE,
    self::REASON_REOPEN,
    self::REASON_OTHER,
  ];

  const STATUS_PENDING = 1;
  const STATUS_RESOLVED = 2;

  /**
   * Returns a localized description for a review queue.
   *
   * @param int $reason One of the self::REASON_* values
   * @return string A localized description
   */
  static function getDescription($reason) {
    switch ($reason) {
      case self::REASON_UNHELPFUL:   return _('items flagged as unhelpful');
      case self::REASON_DUPLICATE:   return _('items flagged as duplicate');
      case self::REASON_FIRST_POST:  return _('first posts');
      case self::REASON_LATE_ANSWER: return _('late answers');
      case self::REASON_CLOSE:       return _('items flagged for closing');
      case self::REASON_REOPEN:      return _('items flagged for reopening');
      case self::REASON_OTHER:       return _('items flagged for other reasons');
    }
  }

  /**
   * Returns a localized URL name for a review queue.
   *
   * @param int $reason One of the self::REASON_* values
   * @return string A localized URL name
   */
  static function getUrlName($reason) {
    switch ($reason) {
      case self::REASON_UNHELPFUL:   return _('unhelpful');
      case self::REASON_DUPLICATE:   return _('duplicate');
      case self::REASON_FIRST_POST:  return _('first-post');
      case self::REASON_LATE_ANSWER: return _('late-answer');
      case self::REASON_CLOSE:       return _('close');
      case self::REASON_REOPEN:      return _('reopen');
      case self::REASON_OTHER:       return _('other');
    }
  }

  /**
   * Returns a review reason given a localized URL name.
   *
   * @param string $urlName A localized URL name
   * @return int One of the self::REASON_* values or null if nothing matches
   */
  static function getReasonFromUrlName($urlName) {
    // do this naively for now
    foreach (self::REASONS as $t) {
      if (self::getUrlName($t) == $urlName) {
        return $t;
      }
    }
    return null;
  }

  static function create($objectType, $objectId, $reason = null) {
    $r = Model::factory('Review')->create();
    $r->objectType = $objectType;
    $r->objectId = $objectId;
    $r->reason = $reason;
    $r->status = self::STATUS_PENDING;
    return $r;
  }


  /**
   * Returns the existing review for this object. If no review exists, starts
   * one for the given reason.
   *
   * @param int $objectType value from Review::TYPE_*
   * @param int $objectId object ID
   * @param int $reason value from Review::REASON_*
   */
  static function ensure($objectType, $objectId, $reason) {
    $r = self::get_by_objectType_objectId_status(
      $objectType, $objectId, self::STATUS_PENDING);

    if (!$r) {
      $r = self::create($objectType, $objectId, $reason);
      $r->save();
    }

    return $r;
  }

  /**
   * Checks if the review can be deleted (if it has no remaining flags).
   */
  function checkDelete() {
    if (!Flag::count_by_reviewId($this->id)) {
      $this->delete();
    }
  }

  function delete() {
    Log::warning('Deleted review %s (objectType %d, objectId %d)',
                 $this->id, $this->objectType, $this->objectId);
    Flag::delete_all_by_reviewId($this->id);
    parent::delete();
  }
}
