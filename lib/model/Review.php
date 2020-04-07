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
  const STATUS_OBJECT_GONE = 4; // closed because a user deleted the underlying object

  const ACTION_CLOSE = 1;
  const ACTION_DELETE = 2;
  const ACTION_INCORPORATE_PENDING_EDIT = 3;
  const ACTION_DELETE_PENDING_EDIT = 4;
  const ACTION_REOPEN = 5;

  // Maps object x reason to methods to run when a review is resolved.
  // It should be impossible to encounter cases not covered here.
  const KEEP_ACTION_MAP = [
    Proto::TYPE_ANSWER => [
      Ct::REASON_PENDING_EDIT => self::ACTION_INCORPORATE_PENDING_EDIT,
      Ct::REASON_REOPEN => self::ACTION_REOPEN,
    ],
    Proto::TYPE_ENTITY => [
      Ct::REASON_PENDING_EDIT => self::ACTION_INCORPORATE_PENDING_EDIT,
      Ct::REASON_REOPEN => self::ACTION_REOPEN,
    ],
    Proto::TYPE_STATEMENT => [
      Ct::REASON_PENDING_EDIT => self::ACTION_INCORPORATE_PENDING_EDIT,
      Ct::REASON_REOPEN => self::ACTION_REOPEN,
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
      Ct::REASON_UNVERIFIABLE => self::ACTION_DELETE,
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
   * Returns a localized description of a review reason, to be displayed in
   * the underlying object's revision history.
   *
   * @param int $reason One of the Ct::REASON_* values
   * @return string A localized description
   */
  function getReasonName() {
    switch ($this->reason) {
      case Ct::REASON_SPAM:         return _('reason-name-spam');
      case Ct::REASON_ABUSE:        return _('reason-name-abuse');
      case Ct::REASON_DUPLICATE:    return _('reason-name-duplicate');
      case Ct::REASON_OFF_TOPIC:    return _('reason-name-off-topic');
      case Ct::REASON_UNVERIFIABLE: return _('reason-name-unverifiable');
      case Ct::REASON_LOW_QUALITY:  return _('reason-name-low-quality');
      case Ct::REASON_NEW_USER:     return _('reason-name-new-user');
      case Ct::REASON_LATE_ANSWER:  return _('reason-name-late-answer');
      case Ct::REASON_REOPEN:       return _('reason-name-reopen');
      case Ct::REASON_PENDING_EDIT: return _('reason-name-pending-edit');
      case Ct::REASON_OTHER:        return _('reason-name-other-reason');
      case Ct::REASON_NOT_NEEDED:   return _('reason-name-not-needed');
    }
  }

  /**
   * Returns a localized description for a review queue.
   *
   * @param int $reason One of the Ct::REASON_* values
   * @return string A localized description
   */
  static function getDescription($reason) {
    switch ($reason) {
      case Ct::REASON_SPAM:         return _('queue-name-spam');
      case Ct::REASON_ABUSE:        return _('queue-name-abuse');
      case Ct::REASON_DUPLICATE:    return _('queue-name-duplicate');
      case Ct::REASON_OFF_TOPIC:    return _('queue-name-off-topic');
      case Ct::REASON_UNVERIFIABLE: return _('queue-name-unverifiable');
      case Ct::REASON_LOW_QUALITY:  return _('queue-name-low-quality');
      case Ct::REASON_NEW_USER:     return _('queue-name-new-user');
      case Ct::REASON_LATE_ANSWER:  return _('queue-name-late-answer');
      case Ct::REASON_REOPEN:       return _('queue-name-reopen');
      case Ct::REASON_PENDING_EDIT: return _('queue-name-pending-edit');
      case Ct::REASON_OTHER:        return _('queue-name-other-reason');
      case Ct::REASON_NOT_NEEDED:   return _('queue-name-not-needed');
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
      case Ct::REASON_SPAM:         return _('queue-url-spam');
      case Ct::REASON_ABUSE:        return _('queue-url-abuse');
      case Ct::REASON_DUPLICATE:    return _('queue-url-duplicate');
      case Ct::REASON_OFF_TOPIC:    return _('queue-url-off-topic');
      case Ct::REASON_UNVERIFIABLE: return _('queue-url-unverifiable');
      case Ct::REASON_LOW_QUALITY:  return _('queue-url-low-quality');
      case Ct::REASON_NEW_USER:     return _('queue-url-new-user');
      case Ct::REASON_LATE_ANSWER:  return _('queue-url-late-answer');
      case Ct::REASON_REOPEN:       return _('queue-url-reopen');
      case Ct::REASON_PENDING_EDIT: return _('queue-url-pending-edit');
      case Ct::REASON_OTHER:        return _('queue-url-other-reason');
      case Ct::REASON_NOT_NEEDED:   return _('queue-url-not-needed');
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
    $r->moderator = (int)$obj->requiresModeratorReview();
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
   * Returns the localized vote name of this flag. This is usually 'keep' or
   * 'remove', but for reopen reviews it changes to 'reopen' or 'ignore'.
   *
   * @return string A localized name.
   */
  function getVoteName($vote) {
    if ($this->reason == Ct::REASON_REOPEN) {
      return ($vote == Flag::VOTE_KEEP)
        ? _('vote-reopen')
        : _('vote-ignore');
    } else {
      return ($vote == Flag::VOTE_KEEP)
        ? _('vote-keep')
        : _('vote-remove');
    }
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
   * starts one. Does not take the moderator bit into account when looking for
   * an existing review.
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
   * Checks if $obj has been recently closed or deleted. If so, places $obj in
   * the reopen queue. To be called whenever $obj is edited.
   *
   * @param Flaggable $obj a flaggable object
   */
  static function checkRecentlyClosedDeleted($obj) {
    $ts = $obj->getDeletionClosureTimestamp();

    if ($ts && ((time() - $ts) / 86400 < Config::EDIT_REOPEN_DAYS)) {
      self::ensure($obj, Ct::REASON_REOPEN);
      FlashMessage::add(_('info-added-reopen-queue'), 'success');
    }
  }

  /**
   * Resolves the review if possible.
   */
  function evaluate() {
    $type = $this->getObject()->getObjectType();

    // It is important to resolve the review (i.e. change its status from
    // pending to something else) before resolving its object. Resolving the
    // object could result in marking it as deleted, which as a side effect
    // resolves all pending reviews for the object. This loop should be
    // avoided.
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
   * Marks the review and its flags as STATUS_STALE or STATUS_OBJECT_GONE.
   * Rejects any pending edits. Throws an exception if called with the wrong
   * status.
   *
   * @param int $status One of Review::STATUS_STALE or Review::STATUS_OBJECT_GONE.
   */
  public function resolveUncommon($status) {
    switch ($status) {
      case self::STATUS_STALE: $flagStatus = Flag::STATUS_STALE; break;
      case self::STATUS_OBJECT_GONE: $flagStatus = Flag::STATUS_OBJECT_GONE; break;
      default: throw new Exception('Invalid uncommon status');
    }

    $this->status = $status;
    $this->save();

    foreach ($this->getFlags() as $f) {
      $f->status = $flagStatus;
      $f->save();
    }

    $obj = $this->getObject();
    if ($obj && $this->reason == Ct::REASON_PENDING_EDIT) {
      $obj->processPendingEdit(false);
    }
  }

  /**
   * If a message was deleted as spam or abuse, penalize its author. Called
   * when the review is resolved by deleting the object.
   */
  private function checkRepPenalty() {
    $obj = $this->getObject();
    if (in_array($this->reason, [ Ct::REASON_SPAM, Ct::REASON_ABUSE ]) &&
        $obj->status != Ct::STATUS_DELETED) { // in case it gets flagged twice
      $obj->getUser()->grantReputation(Config::REP_SPAM_ABUSE);
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
      $this->checkRepPenalty();
      $obj->markDeleted($this->reason);
    } else if ($action == self::ACTION_INCORPORATE_PENDING_EDIT) {
      $obj->processPendingEdit(true);
    } else if ($action == self::ACTION_DELETE_PENDING_EDIT) {
      $obj->processPendingEdit(false);
    } else if ($action == self::ACTION_REOPEN) {
      $obj->reopen();
    } else {
      Log::alert('Invalid action %s encountered in review #%s.', $action, $this->id);
    }
  }

  /**
   * Checks if the review can be deleted (if it has no remaining flags).
   * @return boolean true if the review was deleted, false otherwise
   */
  function checkDelete() {
    if (!in_array($this->reason, self::STICKY) &&
        !Flag::count_by_reviewId($this->id)) {
      $this->delete();
      return true;
    } else {
      return false;
    }
  }

  function delete() {
    Log::warning('Deleted review %s (objectType %d, objectId %d)',
                 $this->id, $this->objectType, $this->objectId);
    Flag::delete_all_by_reviewId($this->id);
    parent::delete();
  }
}
