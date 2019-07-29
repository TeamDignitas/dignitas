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

  function isDeletable() {
    return $this->userId == User::getActiveId();
  }

  function delete() {
    Log::warning("Deleted answer %d (%s)",
                 $this->id, Str::shorten($this->contents, 100));
    Vote::delete_all_by_type_objectId(Vote::TYPE_ANSWER, $this->id);
    parent::delete();
  }
}
