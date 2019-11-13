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
      $this->userId == User::getActiveId();   // can always delete user's own answers
  }

  function markDeleted() {
    $this->status = self::STATUS_DELETED;
    $this->save();
  }

  function delete() {
    throw new Exception('Answers should never actually be deleted.');
  }
}
