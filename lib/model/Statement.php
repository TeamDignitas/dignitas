<?php

class Statement extends BaseObject implements DatedObject {
  use FlaggableTrait, MarkdownTrait, VotableTrait;

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

  /**
   * Returns this Statement's answers, filtered by visibility to the current
   * user.
   */
  function getAnswers() {
    $answers = Model::factory('Answer')
      ->where('statementId', $this->id);

    if (!User::may(User::PRIV_DELETE_ANSWER)) {
      $answers = $answers
        ->where('status', self::STATUS_ACTIVE);
    }

    return $answers
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

  /**
   * If this Statement is closed as a duplicate, returns the duplicate statement;
   * otherwise returns null.
   *
   * @return Statement Statement object or null.
   */
  function getDuplicate() {
    return (($this->status == self::STATUS_CLOSED) && $this->duplicateId)
      ? Statement::get_by_id($this->duplicateId)
      : null;
  }

  /**
   * Returns human-readable information about the status of this Statement.
   *
   * @return array a tuple of (human-readable status, human-readable sentence,
   * CSS class). If the status is active, returns null.
   */
  function getStatusInfo() {
    if ($this->status == self::STATUS_ACTIVE) {
      return null;
    }

    $rec = [];
    $dup = $this->getDuplicate();

    $rec['status'] = $dup
      ? _('duplicate')
      : ($this->status == self::STATUS_CLOSED
         ? _('closed')
         : _('deleted'));

    $rec['dup'] = $dup;

    $rec['cssClass'] = ($this->status == self::STATUS_DELETED)
      ? 'alert-danger'
      : 'alert-warning';

    $rec['details'] = ($this->status == self::STATUS_CLOSED)
      ? _('This statement was closed')
      : _('This statement was deleted');

    $reason = $this->getReviewReason();
    switch ($reason) {
      case Review::REASON_SPAM: $r = _('because it is spam.'); break;
      case Review::REASON_ABUSE: $r = _('because it is rude or abusive.'); break;
      case Review::REASON_DUPLICATE: $r = _('as a duplicate of'); break;
      case Review::REASON_OFF_TOPIC: $r = _('because it is off-topic.'); break;
      case Review::REASON_UNVERIFIABLE: $r = _('because it is unverifiable.'); break;
      case Review::REASON_LOW_QUALITY: $r = _('because it is low-quality.'); break;
      case Review::REASON_OTHER: $r = _('for other reasons.');
      default: $r = '';
    }
    $rec['details'] .= ' ' . $r;

    return $rec;
  }

  function isViewable() {
    return
      ($this->status != self::STATUS_DELETED) ||
      User::may(User::PRIV_DELETE_STATEMENT);
  }

  function isEditable() {
    return
      User::may(User::PRIV_EDIT_STATEMENT) ||  // can edit any statements
      $this->userId == User::getActiveId();    // can always edit user's own statements
  }

  function isDeletable() {
    return
      User::may(User::PRIV_DELETE_STATEMENT) || // can delete any statement
      $this->userId == User::getActiveId();     // can always delete user's own statements
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
