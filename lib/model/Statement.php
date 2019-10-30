<?php

class Statement extends BaseObject implements DatedObject {
  use FlaggableTrait, MarkdownTrait;

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

  // get the current user's vote on this statement
  function getVote() {
    return Vote::get_by_userId_objectType_objectId(
      User::getActiveId(), self::TYPE_STATEMENT, $this->id);
  }

  function delete() {
    Log::warning("Deleted statement {$this->id} ({$this->summary})");
    Answer::delete_all_by_statementId($this->id);
    StatementSource::delete_all_by_statementId($this->id);
    Review::deleteObject($this);
    AttachmentReference::deleteObject($this);
    ObjectTag::deleteObject($this);
    Vote::deleteObject($this);
    parent::delete();
  }

}
