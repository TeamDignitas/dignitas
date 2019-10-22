<?php

class Answer extends BaseObject implements DatedObject {
  use FlaggableTrait, MarkdownTrait;

  function getFlagType() {
    return Flag::TYPE_ANSWER;
  }

  function getObjectType() {
    return ObjectTypes::TYPE_ANSWER;
  }

  function getMarkdownFields() {
    return [ 'contents' ];
  }

  function getUser() {
    return User::get_by_id($this->userId);
  }

  function getStatement() {
    return Statement::get_by_id($this->statementId);
  }

  function sanitize() {
    $this->contents = trim($this->contents);
  }

  // get the current user's vote on this answer
  function getVote() {
    return Vote::get_by_userId_objectType_objectId(
      User::getActiveId(), Vote::TYPE_ANSWER, $this->id);
  }

  function isDeletable() {
    return $this->userId == User::getActiveId();
  }

  function delete() {
    Log::warning("Deleted answer %d (%s)",
                 $this->id, Str::shorten($this->contents, 100));
    $this->deleteFlagsAndQueueItems();
    AttachmentReference::deleteObject($this);
    Vote::deleteObject($this);
    parent::delete();
  }
}
