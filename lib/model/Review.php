<?php

/**
 * Class that handles an object review from start to resolution.
 */
class Review extends Proto {
  use ObjectTypeIdTrait;

  const STATUS_PENDING = 0;
  const STATUS_KEEP = 1;
  const STATUS_REMOVE = 2;
  const STATUS_STALE = 3; // closed due to lack of activity

  const ACTION_CLOSE = 1;
  const ACTION_DELETE = 2;
  const ACTION_INCORPORATE_PENDING_EDIT = 3;
  const ACTION_DELETE_PENDING_EDIT = 4;

  // Maps object x reason to methods to run when a review is resolved.
  // It should be impossible to encounter cases not covered here.
  const KEEP_ACTION_MAP = [
    Proto::TYPE_ANSWER => [
      Ct::REASON_PENDING_EDIT => self::ACTION_INCORPORATE_PENDING_EDIT,
    ],
    Proto::TYPE_ENTITY => [
      Ct::REASON_PENDING_EDIT => self::ACTION_INCORPORATE_PENDING_EDIT,
    ],
    Proto::TYPE_STATEMENT => [
      Ct::REASON_PENDING_EDIT => self::ACTION_INCORPORATE_PENDING_EDIT,
    ],
  ];

  const REMOVE_ACTION_MAP = [
    Proto::TYPE_ANSWER => [
      Ct::REASON_SPAM => self::ACTION_DELETE,
      Ct::REASON_ABUSE => self::ACTION_DELETE,
      Ct::REASON_OFF_TOPIC => self::ACTION_DELETE,
      Ct::REASON_LOW_QUALITY => self::ACTION_DELETE,
      Ct::REASON_NEW_USER => self::ACTION_DELETE,
      Ct::REASON_LATE_ANSWER => self::ACTION_DELETE,
      Ct::REASON_PENDING_EDIT => self::ACTION_DELETE_PENDING_EDIT,
      Ct::REASON_OTHER => self::ACTION_DELETE,
    ],
    Proto::TYPE_ENTITY => [
      Ct::REASON_SPAM => self::ACTION_DELETE,
      Ct::REASON_ABUSE => self::ACTION_DELETE,
      Ct::REASON_DUPLICATE => self::ACTION_CLOSE,
      Ct::REASON_OFF_TOPIC => self::ACTION_DELETE,
      Ct::REASON_NEW_USER => self::ACTION_DELETE,
      Ct::REASON_PENDING_EDIT => self::ACTION_DELETE_PENDING_EDIT,
      Ct::REASON_OTHER => self::ACTION_DELETE,
    ],
    Proto::TYPE_STATEMENT => [
      Ct::REASON_SPAM => self::ACTION_DELETE,
      Ct::REASON_ABUSE => self::ACTION_DELETE,
      Ct::REASON_DUPLICATE => self::ACTION_CLOSE,
      Ct::REASON_OFF_TOPIC => self::ACTION_CLOSE,
      Ct::REASON_UNVERIFIABLE => self::ACTION_CLOSE,
      Ct::REASON_LOW_QUALITY => self::ACTION_CLOSE,
      Ct::REASON_NEW_USER => self::ACTION_CLOSE,
      Ct::REASON_PENDING_EDIT => self::ACTION_DELETE_PENDING_EDIT,
      Ct::REASON_OTHER => self::ACTION_CLOSE,
    ],
    Proto::TYPE_COMMENT => [
      Ct::REASON_SPAM => self::ACTION_DELETE,
      Ct::REASON_ABUSE => self::ACTION_DELETE,
      Ct::REASON_OTHER => self::ACTION_DELETE,
      Ct::REASON_NOT_NEEDED => self::ACTION_DELETE,
    ],
  ];

  // Reviews of this type may not be deleted even when they have no remaining
  // flags (e.g. if the first reviewer retracts their flag).
  const STICKY = [
    Ct::REASON_NEW_USER, Ct::REASON_LATE_ANSWER,
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
      case Ct::REASON_PENDING_EDIT: return _('suggested changes');
      case Ct::REASON_OTHER:        return _('items flagged for other reasons');
      case Ct::REASON_NOT_NEEDED:   return _('no longer needed comments');
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
      case Ct::REASON_PENDING_EDIT: return _('suggested-changes');
      case Ct::REASON_OTHER:        return _('other');
      case Ct::REASON_NOT_NEEDED:   return _('not-needed');
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

  function getUrl() {
    return sprintf('%s/%s/%s',
                   Router::link('review/view'),
                   self::getUrlName($this->reason),
                   $this->id);
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
   * If this Review has type "duplicate of", return the duplicate object;
   * otherwise return null.
   *
   * @return Object A duplicable object or null.
   */
  function getDuplicate() {
    if ($this->reason != Ct::REASON_DUPLICATE) {
      return null;
    }

    switch ($this->objectType) {
      case Proto::TYPE_ENTITY: return Entity::get_by_id($this->duplicateId);
      case Proto::TYPE_STATEMENT: return Statement::get_by_id($this->duplicateId);
      default: return null;
    }
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
      ->where('r.status', self::STATUS_PENDING)
      ->where_null('rl.id')
      ->order_by_desc('r.id')
      ->find_one();
  }

  static function getForObject($object, $reason) {
    return self::get_by_objectType_objectId_reason_status(
      $object->getObjectType(),
      $object->id,
      $reason,
      self::STATUS_PENDING);
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
   * Checks if $obj is from a new user and starts a review if needed.
   * Assumes $obj is a newly-created object.
   *
   * @param Flaggable $obj a flaggable object
   */
  static function checkNewUser($obj) {
    $user = User::getActive();
    if ($user->reputation < Config::NEW_USER_REPUTATION) {
      self::ensure($obj, Ct::REASON_NEW_USER);
    }
  }

  /**
   * Checks if $answer is a late answer and starts a review if needed.
   *
   * @param Answer $answer
   */
  static function checkLateAnswer($answer) {
    $maxAge = Config::LATE_ANSWER_DAYS * Ct::ONE_DAY_IN_SECONDS;
    $st = $answer->getStatement();
    if ($answer->createDate - $st->createDate > $maxAge) {
      self::ensure($answer, Ct::REASON_LATE_ANSWER);
    }
  }

  /**
   * Resolves the review if possible.
   */
  function evaluate() {
    $type = $this->getObject()->getObjectType();

    if ($this->hasEnoughVotes(Flag::VOTE_KEEP, Config::KEEP_VOTES_NECESSARY)) {
      $this->resolve(self::STATUS_KEEP, Flag::VOTE_KEEP);
      $action = self::KEEP_ACTION_MAP[$type][$this->reason] ?? null;
      $this->resolveObject($action);
    } else if ($this->hasEnoughVotes(Flag::VOTE_REMOVE, Config::REMOVE_VOTES_NECESSARY)) {
      $this->resolve(self::STATUS_REMOVE, Flag::VOTE_REMOVE);
      $action = self::REMOVE_ACTION_MAP[$type][$this->reason] ?? null;
      $this->resolveObject($action);
    }
  }

  /**
   * Checks if the review has enough votes to resolve as $vote.
   *
   * @param int $vote one of the Flag::VOTE_* values.
   * @param int $threshold one of the Config::*_VOTES_NECESSARY values.
   * @return boolean
   */
  function hasEnoughVotes($vote, $threshold) {
    // one moderator vote suffices
    $modVote = Flag::get_by_reviewId_weight_vote(
      $this->id, Flag::WEIGHT_MODERATOR, $vote);

    if ($modVote) {
      return true;
    } else {
      // count the executive votes
      $count = Flag::count_by_reviewId_weight_vote(
        $this->id, Flag::WEIGHT_EXECUTIVE, $vote);
      return ($count >= $threshold);
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
   * Marks the review and its flags as STATUS_STALE. Rejects any pending edits.
   */
  public function resolveStale() {
    $this->status = self::STATUS_STALE;
    $this->save();

    foreach ($this->getFlags() as $f) {
      $f->status = Flag::STATUS_STALE;
      $f->save();
    }

    $obj = $this->getObject();
    if ($obj && $this->reason == Ct::REASON_PENDING_EDIT) {
      $obj->processPendingEdit(false);
    }
  }

  /**
   * Closes or deletes the reviewed object.
   *
   * @param int $action One of the Review::ACTION_* values.
   */
  private function resolveObject($action) {
    $obj = $this->getObject();

    if (!$action) {
      // no action defined
    } else if ($action == self::ACTION_CLOSE) {
      if ($this->reason == Ct::REASON_DUPLICATE) {
        $obj->closeAsDuplicate($this->duplicateId);
      } else {
        $obj->close($this->reason);
      }
    } else if ($action == self::ACTION_DELETE) {
      $obj->markDeleted($this->reason);
    } else if ($action == self::ACTION_INCORPORATE_PENDING_EDIT) {
      $obj->processPendingEdit(true);
    } else if ($action == self::ACTION_DELETE_PENDING_EDIT) {
      $obj->processPendingEdit(false);
    } else {
      Log::alert('Invalid action %s encountered in review #%s.', $action, $this->id);
    }
  }

  /**
   * Checks if the review can be deleted (if it has no remaining flags).
   */
  function checkDelete() {
    if (!in_array($this->reason, self::STICKY) &&
        !Flag::count_by_reviewId($this->id)) {
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
