<?php

class Statement extends Proto {
  use DuplicateTrait,
    FlaggableTrait,
    RevisionTrait,
    MarkdownTrait,
    PendingEditTrait,
    VotableTrait;

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

  function getScore() {
    return StatementExt::getField($this->id, 'score');
  }

  function setScore($score) {
    return StatementExt::setField($this->id, 'score', $score);
  }

  /**
   * Returns this Statement's answers, filtered by visibility to the current
   * user.
   */
  function getAnswers() {
    $answers = Model::factory('Answer')
      ->table_alias('a')
      ->select('a.*')
      ->left_outer_join('answer_ext', [ 'a.id', '=', 'ae.answerId' ], 'ae')
      ->where('a.statementId', $this->id)
      ->where_not_equal('a.status', Ct::STATUS_PENDING_EDIT);

    if (!User::may(User::PRIV_DELETE_ANSWER)) {
      $answers = $answers
        ->where('a.status', Ct::STATUS_ACTIVE);
    }

    return $answers
      ->order_by_desc('ae.score')
      ->order_by_desc('a.createDate')
      ->find_many();
  }

  function getLinks() {
    return Link::getFor($this);
  }

  function getTags() {
    return ObjectTag::getTags($this);
  }

  /**
   * Returns human-readable information about the status of this Statement.
   *
   * @return array a tuple of (human-readable status, human-readable sentence,
   * CSS class). If the status is active, returns null.
   */
  function getStatusInfo() {
    if (!in_array($this->status, [Ct::STATUS_CLOSED, Ct::STATUS_DELETED])) {
      return null;
    }

    $rec = [];
    $dup = $this->getDuplicate();

    $rec['status'] = $dup
      ? _('status-statement-duplicate')
      : ($this->status == Ct::STATUS_CLOSED
         ? _('status-statement-closed')
         : _('status-statement-deleted'));

    $rec['dup'] = $dup;

    $rec['cssClass'] = ($this->status == Ct::STATUS_DELETED)
      ? 'alert-danger'
      : 'alert-warning';

    $rec['details'] = ($this->status == Ct::STATUS_CLOSED)
      ? _('info-statement-closed')
      : _('info-statement-deleted');

    switch ($this->reason) {
      case Ct::REASON_SPAM: $r = _('info-statement-spam'); break;
      case Ct::REASON_ABUSE: $r = _('info-statement-abuse'); break;
      case Ct::REASON_DUPLICATE: $r = _('info-statement-duplicate-of'); break;
      case Ct::REASON_OFF_TOPIC: $r = _('info-statement-off-topic'); break;
      case Ct::REASON_UNVERIFIABLE: $r = _('info-statement-unverifiable'); break;
      case Ct::REASON_LOW_QUALITY: $r = _('info-statement-low-quality'); break;
      case Ct::REASON_BY_OWNER: $r = _('info-statement-by-owner'); break;
      case Ct::REASON_BY_USER: $r = _('info-by'); break;
      case Ct::REASON_OTHER: $r = _('info-other-reason'); break;
      default: $r = '';
    }
    $rec['details'] .= ' ' . $r;

    return $rec;
  }

  function isViewable() {
    return
      ($this->status != Ct::STATUS_PENDING_EDIT) &&
      (($this->status != Ct::STATUS_DELETED) ||
       User::may(User::PRIV_DELETE_STATEMENT));
  }

  protected function isEditableCore() {
    if (!$this->id && !User::may(User::PRIV_ADD_STATEMENT)) {
      throw new Exception(sprintf(
        _('info-minimum-reputation-add-statement-%s'),
        Str::formatNumber(User::PRIV_ADD_STATEMENT)));
    }

    if ($this->id &&
        !User::may(User::PRIV_EDIT_STATEMENT) &&  // can edit any statements
        $this->userId != User::getActiveId()) {   // can always edit user's own statements
      throw new Exception(sprintf(
        _('info-minimum-reputation-edit-statement-%s'),
        Str::formatNumber(User::PRIV_EDIT_STATEMENT)));
    }
  }

  /**
   * Checks whether the statement's owner can delete it.
   * Current policy: deletable if
   *   - it has no answers; or
   *   - it has one answer with a zero or negative score.
   *
   * @return bool
   */
  function isDeletableByOwner() {
    $answers = Answer::get_all_by_statementId_status($this->id, Ct::STATUS_ACTIVE);

    switch (count($answers)) {
      case 0: return true;
      case 1: return ($answers[0]->getScore() <= 0);
      default: return false;
    }
  }

  function isDeletable() {
    if (!$this->id) {
      return false; // not on the add statement page
    } else if (in_array($this->status, [Ct::STATUS_DELETED, Ct::STATUS_PENDING_EDIT])) {
      return false; // already deleted or pending edit
    } else if (User::may(User::PRIV_DELETE_STATEMENT)) {
      return true;  // can delete any statement
    } else if (($this->userId == User::getActiveId()) && $this->isDeletableByOwner()) {
      return true;  // owner can delete it
    } else {
      return false;
    }
  }

  function deepClone($root = null, $changes = []) {
    $clone = parent::deepClone($root, $changes);
    foreach ($this->getLinks() as $l) {
      $l->deepClone($clone, [ 'objectId' => $clone->id]);
    }
    foreach (ObjectTag::getObjectTags($this) as $ot) {
      $ot->deepClone($clone, [ 'objectId' => $clone->id]);
    }
    return $clone;
  }

  protected function deepMerge($other) {
    $this->copyFrom($other);
    $this->save($other->modUserId);

    $this->mergeDependants(
      $other, $this->getLinks(), $other->getLinks(), 'objectId');
    $this->mergeDependants(
      $other, ObjectTag::getObjectTags($this), ObjectTag::getObjectTags($other), 'objectId');

    // a pending edit statement should not have answers, reviews or votes
  }

  function delete() {
    if ($this->status != Ct::STATUS_PENDING_EDIT) {
      throw new Exception('Statements should never be deleted at the DB level.');
    }

    Link::deleteObject($this);
    ObjectTag::deleteObject($this);
    AttachmentReference::deleteObject($this);
    // a pending edit statement should not have answers, reviews or votes

    StatementExt::delete_all_by_statementId($this->id);

    parent::delete();
  }
}
