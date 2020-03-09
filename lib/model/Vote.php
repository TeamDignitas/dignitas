<?php

class Vote extends Proto {
  use ObjectTypeIdTrait;

  // cost of upvotes/downvotes to author
  const AUTHOR_REP_COST = [
    Proto::TYPE_STATEMENT => [
      Config::REP_STATEMENT_UPVOTED,
      Config::REP_STATEMENT_DOWNVOTED,
    ],
    Proto::TYPE_ANSWER => [
      Config::REP_ANSWER_UPVOTED,
      Config::REP_ANSWER_DOWNVOTED,
    ],
    Proto::TYPE_COMMENT => [
      Config::REP_COMMENT_UPVOTED,
      Config::REP_COMMENT_DOWNVOTED,
    ],
  ];

  // cost of downvotes to voter
  const VOTER_REP_COST = [
    Proto::TYPE_STATEMENT => Config::REP_DOWNVOTE_STATEMENT,
    Proto::TYPE_ANSWER => Config::REP_DOWNVOTE_ANSWER,
    Proto::TYPE_COMMENT => Config::REP_DOWNVOTE_COMMENT,
    Proto::TYPE_ENTITY => 0, // to "give back" rep when deleting entities
  ];

  static function loadOrCreate($userId, $objectType, $objectId) {
    $vote = self::get_by_userId_objectType_objectId($userId, $objectType, $objectId);
    if (!$vote) {
      $vote = Model::factory('Vote')->create();
      $vote->userId = $userId;
      $vote->objectType = $objectType;
      $vote->objectId = $objectId;
    }
    return $vote;
  }

  // encapsulate it here because we want to stress that every votable object
  // should have a user that created it
  function getObjectUserId() {
    $obj = $this->getObject();
    return $obj->userId ?? null;
  }

  function getObjectScore() {
    $obj = $this->getObject();
    return $obj->getScore();
  }

  /**
   * Grants reputation to the object's author for the given change in vote value.
   *
   * @param int $from old vote value (+/-1 or 0 if there was no previous voute)
   * @param int $to new vote value (+/-1 or 0 if the vote is being deleted)
   */
  private function grantAuthorRep($from, $to) {
    list($up, $down) = self::AUTHOR_REP_COST[$this->objectType];

    $delta = 0;
    switch ($from) {
      case -1: $delta -= $down; break;
      case +1: $delta -= $up; break;
    }
    switch ($to) {
      case -1: $delta += $down; break;
      case +1: $delta += $up; break;
    }

    $obj = $this->getObject();
    $author = User::get_by_id($obj->userId);
    $author->grantReputation($delta);
  }

  /**
   * Grants reputation to the current user for the given change in vote value.
   *
   * @param int $from old vote value (+/-1 or 0 if there was no previous voute)
   * @param int $to new vote value (+/-1 or 0 if the vote is being deleted)
   */
  private function grantVoterRep($from, $to) {
    $down = self::VOTER_REP_COST[$this->objectType];

    $delta = 0;
    if ($from == -1) {
      $delta -= $down;
    }
    if ($to == -1) {
      $delta += $down;
    }

    $voter = User::getActive();
    $voter->grantReputation($delta);
  }

  /**
   * Inserts, updates or deletes the vote. Updates the object's score. Updates
   * the voter and author's reputations.
   *
   * @param int $value Value that the user clicked on. Note that clicking on
   * the already selected value has the effect of deleting the vote.
   */
  function saveValue($value) {

    // sanitize bad values to +1
    $value = ($value == -1) ? -1 : +1;

    // clicking the same value again means we delete the vote
    if ($this->value == $value) {
      $value = 0;
    }

    $this->getObject()->changeScore($value - $this->value);
    $this->grantAuthorRep($this->value, $value);
    $this->grantVoterRep($this->value, $value);

    if ($value == 0) {
      $this->delete();
    } else {
      $this->value = $value;
      $this->save();
    }
  }

}
