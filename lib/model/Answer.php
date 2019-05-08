<?php

class Answer extends BaseObject implements DatedObject {

  function getUser() {
    return User::get_by_id($this->userId);
  }

  function sanitize() {
    $this->contents = trim($this->contents);
  }

  // get the current user's vote on this answer
  function getVote() {
    return Vote::get_by_userId_type_objectId(
      User::getActiveId(), Vote::TYPE_ANSWER, $this->id);
  }

}
