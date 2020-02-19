<?php

/**
 * Method implementations for objects that can be voted.
 */
trait VotableTrait {

  /**
   * Returns the current user's vote on this object.
   */
  function getVote() {
    return Vote::get_by_userId_objectType_objectId(
      User::getActiveId(), $this->getObjectType(), $this->id);
  }

  abstract function getScore();
  abstract function setScore($score);

  function changeScore($delta) {
    $this->setScore($this->getScore() + $delta);
  }
}
