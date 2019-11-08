<?php

class Answer extends BaseObject implements DatedObject {
  use FlaggableTrait, MarkdownTrait, VotableTrait;

  function getObjectType() {
    return self::TYPE_ANSWER;
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

  /**
   * Create a blank answer assigned to a statement.
   *
   * @param int $statementId
   * @return Answer
   */
  static function create($statementId) {
    $a = Model::factory('Answer')->create();
    $a->statementId = $statementId;
    return $a;
  }

  function sanitize() {
    $this->contents = trim($this->contents);
  }

  function isEditable() {
    return
      User::may(User::PRIV_EDIT_ANSWER) ||    // can edit any answers
      $this->userId == User::getActiveId();   // can always edit user's own answers
  }

  function isDeletable() {
    return
      User::may(User::PRIV_DELETE_ANSWER) ||  // can delete any answer
      $this->userId == User::getActiveId();   // can always delete user's own answers
  }

  function delete() {
    Log::warning("Deleted answer %d (%s)",
                 $this->id, Str::shorten($this->contents, 100));
    Review::deleteObject($this);
    AttachmentReference::deleteObject($this);
    Vote::deleteObject($this);
    parent::delete();
  }
}
