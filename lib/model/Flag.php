<?php

class Flag extends Proto {

  // Boolean constants, but hopefully clearer.
  const VOTE_KEEP = 0;
  const VOTE_REMOVE = 1;

  // Whether the flag was raised by a privileged user.
  const WEIGHT_ADVISORY = 0;
  const WEIGHT_EXECUTIVE = 1;
  const WEIGHT_MODERATOR = 2;

  const STATUS_PENDING = 0;
  const STATUS_ACCEPTED = 1;
  const STATUS_DECLINED = 2;
  const STATUS_STALE = 3; // closed due to lack of activity
  const STATUS_OBJECT_GONE = 4; // closed because a user deleted the review's object

  /**
   * Returns a localized name for a vote's weight.
   *
   * @return string A localized name.
   */
  function getWeightName() {
    switch ($this->weight) {
      case self::WEIGHT_ADVISORY: return _('flag-advisory');
      case self::WEIGHT_EXECUTIVE: return _('flag-executive');
      case self::WEIGHT_MODERATOR: return _('flag-moderator');
      default: return '';
    }
  }

  static function create($reviewId, $details, $vote) {
    $f = Model::factory('Flag')->create();
    $f->userId = User::getActiveId();
    $f->reviewId = $reviewId;
    $f->details = $details ?? '';
    $f->vote = $vote;
    $f->weight = self::getWeight();
    $f->status = self::STATUS_PENDING;
    return $f;
  }

  function getUser() {
    return User::get_by_id($this->userId);
  }

  /**
   * Returns the flag weight for the active user.
   *
   * @return int One of the Flag::WEIGHT_* values.
   */
  static function getWeight() {
    if (User::isModerator()) {
      return self::WEIGHT_MODERATOR;
    } else if (User::may(User::PRIV_REVIEW)) {
      return self::WEIGHT_EXECUTIVE;
    } else {
      return self::WEIGHT_ADVISORY;
    }
  }

}
