<?php

class Statement extends BaseObject implements DatedObject {
  use FlaggableTrait, MarkdownTrait, VotableTrait;

  // for clarity, keep in sync with Answer equivalents
  const STATUS_ACTIVE = 0;
  const STATUS_CLOSED = 1;
  const STATUS_DELETED = 2;

  function getObjectType() {
    return self::TYPE_STATEMENT;
  }

  function getMarkdownFields() {
    return [ 'context' ];
  }

  function getEntity() {
    return Entity::get_by_id($this->entityId);
  }

  function getUser() {
    return User::get_by_id($this->userId);
  }

  function getAnswers() {
    return Model::factory('Answer')
      ->where('statementId', $this->id)
      ->order_by_desc('createDate')
      ->find_many();
  }

  function getSources() {
    return Model::factory('StatementSource')
      ->where('statementId', $this->id)
      ->order_by_asc('rank')
      ->find_many();
  }

  function getTags() {
    return ObjectTag::getTags($this);
  }

  function isEditable() {
    return
      User::may(User::PRIV_EDIT_STATEMENT) ||  // can edit any statements
      $this->userId == User::getActiveId();    // can always edit user's own statements
  }

  function close() {
    $this->status = self::STATUS_CLOSED;
    $this->save();
  }

  function closeAsDuplicate($duplicateId) {
    $this->duplicateId = $duplicateId;
    $this->close();
  }

  function markDeleted() {
    $this->status = self::STATUS_DELETED;
    $this->save();
  }

  function delete() {
    throw new Exception('Statements should never actually be deleted.');
  }

}
