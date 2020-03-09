<?php

class Statement extends Proto {
  use DuplicateTrait,
    FlaggableTrait,
    RevisionTrait,
    MarkdownTrait,
    PendingEditTrait,
    VerdictTrait,
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
    // first load all the answers
    $answers = Model::factory('Answer')
      ->table_alias('a')
      ->select('a.*')
      ->left_outer_join('answer_ext', [ 'a.id', '=', 'ae.answerId' ], 'ae')
      ->where('a.statementId', $this->id)
      ->where_not_equal('a.status', Ct::STATUS_PENDING_EDIT)
      ->order_by_desc('proof')
      ->order_by_desc('ae.score')
      ->order_by_desc('a.createDate')
      ->find_many();

    // then filter by visibility, as the logic is complex enough
    $results = [];
    foreach ($answers as $a) {
      if ($a->isViewable()) {
        $results[] = $a;
      }
    }
    return $results;
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

    if ($this->verdict != Ct::VERDICT_NONE &&
        !User::isModerator()) {
      throw new Exception(_('info-only-moderator-edit-statement-verdict'));
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

  /**
   * Returns a list of statements having bad/suspicious verdicts.
   * Groups the IDs in three categories:
   *
   *   - statement has a verdict, but no proof answers;
   *   - statement has no verdict, but has proof answers;
   *   - statement has a verdict and proof answers with a different verdict.
   *
   * Includes a 'count' key in the final array so the caller can know if there
   * are any results without having to perform the addition.
   **/
  static function getStatementsWithBadVerdicts() {
    // statements with verdicts
    $haveVerdict = Model::factory('Statement')
      ->select('id')
      ->where('status', Ct::STATUS_ACTIVE)
      ->where_not_equal('verdict', Ct::VERDICT_NONE)
      ->find_array();
    $haveVerdict = array_column($haveVerdict, 'id');

    // statements with answers with proofs
    $haveProof = Model::factory('Statement')
      ->table_alias('s')
      ->select('s.id')
      ->distinct()
      ->join('answer', ['s.id', '=', 'a.statementId'], 'a')
      ->where('s.status', Ct::STATUS_ACTIVE)
      ->where('a.status', Ct::STATUS_ACTIVE)
      ->where('a.proof', true)
      ->where_not_equal('a.verdict', Ct::VERDICT_NONE)
      ->find_array();
    $haveProof = array_column($haveProof, 'id');

    // statement-answer pairs with mismatched verdicts
    $verdictMismatch = Model::factory('Statement')
      ->table_alias('s')
      ->select('s.id')
      ->distinct()
      ->join('answer', ['s.id', '=', 'a.statementId'], 'a')
      ->where('s.status', Ct::STATUS_ACTIVE)
      ->where('a.status', Ct::STATUS_ACTIVE)
      ->where('a.proof', true)
      ->where_any_is([
        ['s.verdict' => Ct::VERDICT_TRUE, 'a.verdict' => Ct::VERDICT_FALSE],
        ['s.verdict' => Ct::VERDICT_FALSE, 'a.verdict' => Ct::VERDICT_TRUE],
      ])
      ->find_array();
    $verdictMismatch = array_column($verdictMismatch, 'id');

    $results = [
      'proofNoVerdict' => array_diff($haveProof, $haveVerdict),
      'verdictNoProof' => array_diff($haveVerdict, $haveProof),
      'verdictMismatch' => $verdictMismatch,
    ];

    // now replace IDs with statements
    foreach ($results as $category => $ids) {
      foreach ($ids as $i => &$id) {
        $results[$category][$i] = Statement::get_by_id($id);
      }
    }

    $results['count'] =
      count($results['proofNoVerdict']) +
      count($results['verdictNoProof']) +
      count($results['verdictMismatch']);

    return $results;
  }
}
