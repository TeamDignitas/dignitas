<?php

class Answer extends BaseObject implements DatedObject {
  use FlaggableTrait, MarkdownTrait;

  function getFlagType() {
    return Flag::TYPE_ANSWER;
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
    AttachmentReference::delete_all_by_objectClass_objectId('answer', $this->id);
    parent::delete();
  }
}
