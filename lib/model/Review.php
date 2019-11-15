<?php

/**
 * Class that handles an object review from start to resolution.
 */
class Review extends BaseObject implements DatedObject {
  use ObjectTypeIdTrait;

  const STATUS_PENDING = 0;
  const STATUS_KEEP = 1;
  const STATUS_REMOVE = 2;

  const ACTION_CLOSE = 1;
  const ACTION_DELETE = 2;

  // Maps object x reason to methods to run when a review is evaluated.
  // It should be impossible to encounter cases not covered here.
  const ACTION_MAP = [
    BaseObject::TYPE_ANSWER => [
      Ct::REASON_SPAM => self::ACTION_DELETE,
      Ct::REASON_ABUSE => self::ACTION_DELETE,
      Ct::REASON_OFF_TOPIC => self::ACTION_DELETE,
      Ct::REASON_LOW_QUALITY => self::ACTION_DELETE,
      Ct::REASON_NEW_USER => self::ACTION_DELETE,
      Ct::REASON_OTHER => self::ACTION_DELETE,
    ],
    BaseObject::TYPE_STATEMENT => [
      Ct::REASON_SPAM => self::ACTION_DELETE,
      Ct::REASON_ABUSE => self::ACTION_DELETE,
      Ct::REASON_DUPLICATE => self::ACTION_CLOSE,
      Ct::REASON_OFF_TOPIC => self::ACTION_CLOSE,
      Ct::REASON_UNVERIFIABLE => self::ACTION_CLOSE,
      Ct::REASON_LOW_QUALITY => self::ACTION_CLOSE,
      Ct::REASON_NEW_USER => self::ACTION_CLOSE,
      Ct::REASON_OTHER => self::ACTION_CLOSE,
    ],
  ];

  /**
   * Returns a localized description for a review queue.
   *
   * @param int $reason One of the Ct::REASON_* values
   * @return string A localized description
   */
  static function getDescription($reason) {
    switch ($reason) {
      case Ct::REASON_SPAM:         return _('items flagged as spam');
      case Ct::REASON_ABUSE:        return _('items flagged as abuse');
      case Ct::REASON_DUPLICATE:    return _('items flagged as duplicate');
      case Ct::REASON_OFF_TOPIC:    return _('items flagged as off-topic');
      case Ct::REASON_UNVERIFIABLE: return _('items flagged as unverifiable');
      case Ct::REASON_LOW_QUALITY:  return _('items flagged as low quality');
      case Ct::REASON_NEW_USER:     return _('posts from new users');
      case Ct::REASON_LATE_ANSWER:  return _('late answers');
      case Ct::REASON_REOPEN:       return _('items flagged for reopening');
      case Ct::REASON_OTHER:        return _('items flagged for other reasons');
    }
  }

  /**
   * Returns a localized URL name for a review queue.
   *
   * @param int $reason One of the Ct::REASON_* values
   * @return string A localized URL name
   */
  static function getUrlName($reason) {
    switch ($reason) {
      case Ct::REASON_SPAM:         return _('spam');
      case Ct::REASON_ABUSE:        return _('abuse');
      case Ct::REASON_DUPLICATE:    return _('duplicate');
      case Ct::REASON_OFF_TOPIC:    return _('off-topic');
      case Ct::REASON_UNVERIFIABLE: return _('unverifiable');
      case Ct::REASON_LOW_QUALITY:  return _('low-quality');
      case Ct::REASON_NEW_USER:     return _('new-user');
      case Ct::REASON_LATE_ANSWER:  return _('late-answer');
      case Ct::REASON_REOPEN:       return _('reopen');
      case Ct::REASON_OTHER:        return _('other');
    }
  }

  /**
   * Returns a review reason given a localized URL name.
   *
   * @param string $urlName A localized URL name
   * @return int One of the Ct::REASON_* values or null if nothing matches
   */
  static function getReasonFromUrlName($urlName) {
    // do this naively for now
    for ($r = 1; $r <= Ct::NUM_REASONS; $r++) {
      if (self::getUrlName($r) == $urlName) {
        return $r;
      }
    }
    return null;
  }

  /**
   * Creates a Review for the given object and reason
   *
   * @param Flaggable $obj A flaggable object
   * @param int $reason One of the Ct::REASON_* values
   * @return Review A new Review
   */
  static function create($obj, $reason, $duplicateId) {
    $r = Model::factory('Review')->create();
    $r->objectType = $obj->getObjectType();
    $r->objectId = $obj->id;
    $r->reason = $reason;
    if ($r->reason == Ct::REASON_DUPLICATE) {
      $r->duplicateId = $duplicateId;
    }
    $r->status = self::STATUS_PENDING;
    return $r;
  }

  /**
   * Loads the flags for this review in reverse chronological order.
   *
   * @return array Array of Flag objects.
   */
  function getFlags() {
  return Model::factory('Flag')
    ->where('reviewId', $this->id)
    ->order_by_desc('createDate')
    ->find_many();
  }

  /**
   * If this Review has type "duplicate of", return the duplicate statement;
   * otherwise return null.
   *
   * @return Statement Statement object or null.
   */
  function getDuplicate() {
    return ($this->reason == Ct::REASON_DUPLICATE)
      ? Statement::get_by_id($this->duplicateId)
      : null;
  }

  /**
   * Loads a review to present to the given user. Filters out reviews that the
   * user has already signed off.
   *
   * @param int $userId User ID
   * @param int $reason One of Ct::REASON_* values.
   * @return Review a review object or null if one does not exist.
   */
  static function load($userId, $reason) {
    return Model::factory('Review')
      ->table_alias('r')
      ->select('r.*')
      ->raw_join(
        'left join review_log',
        'r.id = rl.reviewId and rl.userId = ?',
        'rl',
        [$userId])
      ->where('r.reason', $reason)
      ->where('r.status', Review::STATUS_PENDING)
      ->where_null('rl.id')
      ->order_by_desc('r.id')
      ->find_one();
  }

  /**
   * Returns the review for this object and reason. If no review exists,
   * starts one.
   *
   * @param Flaggable $obj a flaggable object
   * @param int $reason value from Ct::REASON_*
   * @param int $duplicateId a Statement ID if $reason = REASON_DUPLICATE, null otherwise
   */
  static function ensure($obj, $reason, $duplicateId = 0) {
    $r = self::get_by_objectType_objectId_reason_duplicateId_status(
      $obj->getObjectType(), $obj->id, $reason, $duplicateId, self::STATUS_PENDING);

    if (!$r) {
      $r = self::create($obj, $reason, $duplicateId);
      $r->save();
    }

    return $r;
  }

  /**
   * Checks if $obj is from a new user starts a review if needed.
   * Assumes $obj is a newly-created object.
   *
   * @param Flaggable $obj a flaggable object
   */
  static function checkNewUser($obj) {
    $user = User::getActive();
    if ($user->reputation < Config::NEW_USER_REPUTATION) {
      Review::ensure($obj, Ct::REASON_NEW_USER);
    }
  }

  /**
   * Resolves the review if possible.
   */
  function evaluate() {
    // never resolve reviews for which no resolution action is defined
    $type = $this->getObject()->getObjectType();
    $action = self::ACTION_MAP[$type][$this->reason] ?? null;

    if (!$action) {
      return;
    }

    $keepVotes = Flag::count_by_reviewId_weight_vote(
      $this->id, Flag::WEIGHT_EXECUTIVE, Flag::VOTE_KEEP);
    $removeVotes = Flag::count_by_reviewId_weight_vote(
      $this->id, Flag::WEIGHT_EXECUTIVE, Flag::VOTE_REMOVE);

    if ($keepVotes >= Config::KEEP_VOTES_NECESSARY) {
      $this->resolve(Review::STATUS_KEEP, Flag::VOTE_KEEP);
    } else if ($removeVotes >= Config::REMOVE_VOTES_NECESSARY) {
      $this->resolve(Review::STATUS_REMOVE, Flag::VOTE_REMOVE);
      $this->resolveObject($action);
    }
  }

  /**
   * Updates the review status and marks its flags as accepted or declined.
   *
   * @param int $status One of the Review::STATUS_* values.
   * @param int $winningVote One of the Flag::VOTE_* values.
   */
  private function resolve($status, $winningVote) {
    $this->status = $status;
    $this->save();
    $this->resolveFlags($winningVote);
  }

  /**
   * Marks flags aligned with $winningVote as accepted, the other ones as
   * declined.
   */
  private function resolveFlags($winningVote) {
    foreach ($this->getFlags() as $f) {
      $f->status = ($f->vote == $winningVote)
        ? Flag::STATUS_ACCEPTED
        : Flag::STATUS_DECLINED;
      $f->save();
    }
  }

  /**
   * Closes or deletes the reviewed object.
   *
   * @param int $action One of the Review::ACTION_* values.
   */
  private function resolveObject($action) {
    $obj = $this->getObject();

    if ($action == self::ACTION_CLOSE) {
      if ($this->reason == Ct::REASON_DUPLICATE) {
        $obj->closeAsDuplicate($this->duplicateId);
      } else {
        $obj->close($this->reason);
      }
    } else if ($action == self::ACTION_DELETE) {
      $obj->markDeleted($this->reason);
    } else {
      Log::alert('Invalid action %s encountered in review #%s.', $action, $this->id);
    }
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