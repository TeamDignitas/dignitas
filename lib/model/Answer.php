<?php

class Answer extends BaseObject {
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
   * Returns a human-readable message if this Answer is deleted or null
   * otherwise.
   *
   * @return string
   */
  function getDeletedMessage() {
    if ($this->status != Ct::STATUS_DELETED) {
      return null;
    }

    $msg = _('This answer was deleted');

    switch ($this->reason) {
      case Ct::REASON_SPAM: $r = _('because it is spam.'); break;
      case Ct::REASON_ABUSE: $r = _('because it is rude or abusive.'); break;
      case Ct::REASON_OFF_TOPIC: $r = _('because it is off-topic.'); break;
      case Ct::REASON_LOW_QUALITY: $r = _('because it is low-quality.'); break;
      case Ct::REASON_BY_OWNER: $r = _('by its author.'); break;
      case Ct::REASON_BY_USER: $r = _('by'); break;
      case Ct::REASON_OTHER: $r = _('for other reasons.'); break;
      default: $r = '';
    }

    return $msg . ' ' . $r;
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
      ($this->status != Ct::STATUS_PENDING_EDIT) &&
      (User::may(User::PRIV_EDIT_ANSWER) ||     // can edit any answers
       $this->userId == User::getActiveId());   // can always edit user's own answers
  }

  /**
   * Checks whether the active user may delete this answer.
   *
   * @return boolean
   */
  function isDeletable() {
    // TODO: not deletable if accepted
    return
      $this->status == Ct::STATUS_ACTIVE &&
      (User::may(User::PRIV_DELETE_ANSWER) ||    // can delete any answer
       $this->userId == User::getActiveId());    // can always delete user's own answers
  }

  function close($reason) {
    throw new Exception('Answers should never be closed.');
  }

  function closeAsDuplicate($duplicateId) {
    throw new Exception('Answers should never be closed as a duplicate.');
  }

}
