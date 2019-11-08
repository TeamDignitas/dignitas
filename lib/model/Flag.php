<?php

class Flag extends BaseObject implements DatedObject {

  // Boolean constants, but hopefully clearer.
  const VOTE_NAY = 0;
  const VOTE_YEA = 1;

  // Whether the flag was raised by a privileged user.
  const WEIGHT_ADVISORY = 0;
  const WEIGHT_EXECUTIVE = 1;

  const STATUS_PENDING = 0;
  const STATUS_ACCEPTED = 1;
  const STATUS_DECLINED = 2;

  /**
   * Returns a localized name for a yea/nay vote.
   *
   * @return string A localized name.
   */
  function getVoteName() {
    return ($this->vote == self::VOTE_YEA)
      ? _('yea')
      : _('nay');
  }

  static function create($reviewId, $details, $vote) {
    $f = Model::factory('Flag')->create();
    $f->userId = User::getActiveId();
    $f->reviewId = $reviewId;
    $f->details = $details ?? '';
    $f->vote = $vote;
    $f->weight = User::may(User::PRIV_CLOSE_REOPEN_VOTE)
      ? self::WEIGHT_EXECUTIVE
      : self::WEIGHT_ADVISORY;
    $f->status = self::STATUS_PENDING;
    return $f;
  }

  function getUser() {
    return User::get_by_id($this->userId);
  }

}
