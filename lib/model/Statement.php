<?php

class Statement extends Proto {
  use ArchivableLinksTrait,
    DraftTrait,
    DuplicateTrait,
    FlaggableTrait,
    RevisionTrait,
    MarkdownTrait,
    PendingEditTrait,
    VerdictTrait,
    VotableTrait;

  const TYPE_ANY = 0; // not a real type, but used by statement filters
  const TYPE_CLAIM = 1;
  const TYPE_FLOP = 2;
  const TYPE_PROMISE = 3;
  const NUM_TYPES = 4;

  const MAX_SUMMARY_LENGTH = 500;
  const MAX_GOAL_LENGTH = 500;

  // number of statements per page for paginated statement lists
  const PAGE_SIZE = 20;

  // Verdicts for all statements
  const VERDICT_NONE = 0;
  const VERDICT_UNDECIDABLE = 1;

  // Verdicts for claims
  const VERDICT_FALSE = 2;
  const VERDICT_MOSTLY_FALSE = 3;
  const VERDICT_MIXED = 4;
  const VERDICT_MOSTLY_TRUE = 5;
  const VERDICT_TRUE = 6;

  // Verdicts for flops
  const VERDICT_FLOP = 7;
  const VERDICT_HALF_FLOP = 8;
  const VERDICT_NO_FLOP = 9;

  // Verdicts for promises
  const VERDICT_PROMISE_BROKEN = 10;
  const VERDICT_PROMISE_STALLED = 11;
  const VERDICT_PROMISE_PARTIAL = 12;
  const VERDICT_PROMISE_KEPT_LATE = 13;
  const VERDICT_PROMISE_KEPT = 14;

  // Also applicable to answers.
  const VERDICTS_BY_TYPE = [
    self::TYPE_ANY => [
      self::VERDICT_NONE,
      self::VERDICT_UNDECIDABLE,
    ],
    self::TYPE_CLAIM => [
      self::VERDICT_NONE,
      self::VERDICT_UNDECIDABLE,
      self::VERDICT_FALSE,
      self::VERDICT_MOSTLY_FALSE,
      self::VERDICT_MIXED,
      self::VERDICT_MOSTLY_TRUE,
      self::VERDICT_TRUE,
    ],
    self::TYPE_FLOP => [
      self::VERDICT_NONE,
      self::VERDICT_UNDECIDABLE,
      self::VERDICT_FLOP,
      self::VERDICT_HALF_FLOP,
      self::VERDICT_NO_FLOP,
    ],
    self::TYPE_PROMISE => [
      self::VERDICT_NONE,
      self::VERDICT_UNDECIDABLE,
      self::VERDICT_PROMISE_BROKEN,
      self::VERDICT_PROMISE_STALLED,
      self::VERDICT_PROMISE_PARTIAL,
      self::VERDICT_PROMISE_KEPT_LATE,
      self::VERDICT_PROMISE_KEPT,
    ],
  ];

  // Suspicious pairs of statement and answer verdicts which will appear on
  // the verdict report. Each pair below goes both ways: [ A, B ] can mean
  // [ statement verdict = A, answer verdict = B ] or viceversa.
  const BAD_VERDICTS = [
    [ self::VERDICT_TRUE, self::VERDICT_FALSE ],
    [ self::VERDICT_TRUE, self::VERDICT_MOSTLY_FALSE ],
    [ self::VERDICT_MOSTLY_TRUE, self::VERDICT_FALSE ],
    [ self::VERDICT_NO_FLOP, self::VERDICT_FLOP ],
    [ self::VERDICT_PROMISE_KEPT, self::VERDICT_PROMISE_STALLED ],
    [ self::VERDICT_PROMISE_KEPT, self::VERDICT_PROMISE_BROKEN ],
    [ self::VERDICT_PROMISE_KEPT_LATE, self::VERDICT_PROMISE_STALLED ],
    [ self::VERDICT_PROMISE_KEPT_LATE, self::VERDICT_PROMISE_BROKEN ],
  ];

  // For ClaimReview ratings
  const CR_WORST_RATING = 1;
  const CR_BEST_RATING = 5;
  const CR_RATINGS = [
    self::VERDICT_FALSE => 1,
    self::VERDICT_MOSTLY_FALSE => 2,
    self::VERDICT_MIXED => 3,
    self::VERDICT_MOSTLY_TRUE => 4,
    self::VERDICT_TRUE => 5,
  ];

  static function create($entityId) {
    $s = Model::factory('Statement')->create();
    $s->dateMade = Time::today();
    $s->type = Statement::TYPE_CLAIM;
    $s->userId = User::getActiveId();
    $s->entityId = $entityId;
    $s->verdict = self::VERDICT_NONE;
    $s->verdictDate = time();
    $s->status = Ct::STATUS_DRAFT;
    return $s;
  }

  function getObjectType() {
    return self::TYPE_STATEMENT;
  }

  static function typeName($type) {
    switch ($type) {
      case self::TYPE_ANY:     return _('statement-type-any');
      case self::TYPE_CLAIM:   return _('statement-type-claim');
      case self::TYPE_FLOP:    return _('statement-type-flop');
      case self::TYPE_PROMISE: return _('statement-type-promise');
    }
  }

  /**
   * Necessary because some languages use the genitive case.
   */
  function getVerdictLabel() {
    switch ($this->type) {
      case self::TYPE_CLAIM:   return _('label-claim-verdict');
      case self::TYPE_FLOP:   return _('label-flop-verdict');
      case self::TYPE_PROMISE:   return _('label-promise-verdict');
      default: return _('label-verdict');
    }
  }

  function getTypeName() {
    return self::typeName($this->type);
  }

  function getVerdictChoices() {
    return self::getVerdictsByType($this->type);
  }

  static function getVerdictsByType($type) {
    return self::VERDICTS_BY_TYPE[$type];
  }

  function updateVerdictDate(int $origVerdict) {
    if ($this->verdict != $origVerdict) {
      $this->verdictDate = time();
    }
  }

  function getViewUrl($absolute = false) {
    return Router::link('statement/view', $absolute) . '/' . $this->id;
  }

  function getEditUrl() {
    return Router::link('statement/edit') . '/' . $this->id;
  }

  function getHistoryUrl() {
    return Router::link('statement/history') . '/' . $this->id;
  }

  function getMarkdownFields() {
    return [ 'context' ];
  }

  function getArchivableUrls() {
    if (in_array($this->status, [ Ct::STATUS_ACTIVE, Ct::STATUS_CLOSED ])) {
      return self::extractArchivableUrls($this->context);
    } else {
      return [];
    }
  }

  function requiresModeratorReview() {
    return ($this->verdict != self::VERDICT_NONE);
  }

  function getEntity() {
    return Entity::get_by_id($this->entityId);
  }

  function getRegion() {
    return Region::get_by_id($this->regionId);
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

  /**
   * @return true if the user should get a warning before changing the
   * statement's type. This is necessary when there are answers with verdicts.
   */
  function needsTypeChangeWarning() {
    return Model::factory('Answer')
      ->where('statementId', $this->id)
      ->where_not_equal('verdict', Statement::VERDICT_NONE)
      ->count();
  }

  function getLinks() {
    return Link::getFor($this);
  }

  function getTags() {
    return ObjectTag::getTags($this);
  }

  function getInvolvements() {
    return Involvement::getFor($this);
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
      (($this->status != Ct::STATUS_DELETED) ||  // active statement
       User::may(User::PRIV_DELETE_STATEMENT) || // privileged user
       (User::getActiveId() == $this->userId));  // owner
  }

  /**
   * Adds clauses to a SQL query to select only those that are visible to the
   * current user. This is necessary for pagination because we cannot filter
   * the statements afterwards.
   * Keep this in sync with isViewable().
   */
  static function filterViewable(ORMWrapper $query) {
    $query = $query->where_not_in(
      'status',
      [ Ct::STATUS_PENDING_EDIT, Ct::STATUS_DRAFT ]);

    if (!User::may(User::PRIV_DELETE_STATEMENT)) { // unprivileged user
      $query = $query->where_raw(
        '(status != ?) OR (userId = ?)',
        [ Ct::STATUS_DELETED, User::getActiveId() ]);
    }

    return $query;
  }

  static function getNumPages(ORMWrapper $query) {
    $numStatements = $query->count();
    return ceil($numStatements / self::PAGE_SIZE);
  }

  /**
   * @param int $page 1-based page number to load
   * @return Statement[]
   */
  static function getPage(ORMWrapper $query, int $page) {
    return $query
      ->order_by_desc('verdictDate')
      ->offset(($page - 1) * self::PAGE_SIZE)
      ->limit(self::PAGE_SIZE)
      ->find_many();
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

    if ($this->verdict != self::VERDICT_NONE &&
        !User::isModerator()) {
      throw new Exception(_('info-only-moderator-edit-statement-verdict'));
    }

    if (!$this->id && Ban::exists(Ban::TYPE_ADD_STATEMENT)) {
      throw new Exception(_('info-banned-add-statement'));
    }

    if ($this->id && Ban::exists(Ban::TYPE_EDIT_STATEMENT)) {
      throw new Exception(_('info-banned-edit-statement'));
    }
  }

  /**
   * Checks whether the current user can add an answer.
   **/
  function isAnswerable() {
    return User::may(User::PRIV_ADD_ANSWER) &&
      !Ban::exists(Ban::TYPE_ADD_ANSWER) &&
      $this->status == Ct::STATUS_ACTIVE;
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
    } else if (in_array($this->status,
                        [Ct::STATUS_DELETED, Ct::STATUS_PENDING_EDIT, Ct::STATUS_DRAFT])) {
      return false; // already deleted or pending edit
    } else if (($this->verdict != self::VERDICT_NONE) && !User::isModerator()) {
      return false; // only moderators can delete statements with verdicts
    } else if (Ban::exists(Ban::TYPE_DELETE)) {
      return false; // banned from deleting objects
    } else if (User::may(User::PRIV_DELETE_STATEMENT)) {
      return true;  // can delete any statement
    } else if (($this->userId == User::getActiveId()) && $this->isDeletableByOwner()) {
      return true;  // owner can delete it
    } else {
      return false;
    }
  }

  /**
   * Checks whether the active user may reopen this statement.
   *
   * @return boolean
   */
  function isReopenable() {
    return
      in_array($this->status, [ Ct::STATUS_CLOSED, Ct::STATUS_DELETED ]) &&
      User::isModerator();
  }

  function deepClone($root = null, $changes = []) {
    $clone = parent::deepClone($root, $changes);
    foreach ($this->getLinks() as $l) {
      $l->deepClone($clone, [ 'objectId' => $clone->id]);
    }
    foreach (ObjectTag::getObjectTags($this) as $ot) {
      $ot->deepClone($clone, [ 'objectId' => $clone->id]);
    }
    foreach ($this->getInvolvements() as $inv) {
      $inv->deepClone($clone, [ 'statementId' => $clone->id]);
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
    $this->mergeDependants(
      $other, $this->getInvolvements(), $other->getInvolvements(), 'statementId');

    // a pending edit statement should not have answers, reviews or votes
  }

  /**
   * Subscribes the author of the most recent change to this statement. Call
   * after saving the statement.
   */
  function subscribe() {
    if ($this->status != Ct::STATUS_PENDING_EDIT) {
      $mask = ($this->modUserId == $this->userId)
        ? Notification::TYPE_ALL
        : Notification::TYPE_CHANGES;
      Subscription::subscribe($this, $this->modUserId, $mask);
    }
  }

  function notify(int $type = Notification::TYPE_CHANGES, $delegate = null) {
    if ($this->status != Ct::STATUS_PENDING_EDIT) {
      Notification::notify($this, $type, $delegate);
      Notification::notifyMentions($this, 'context');
    }
  }

  function delete() {
    if (!in_array($this->status, [ Ct::STATUS_PENDING_EDIT, Ct::STATUS_DRAFT ])) {
      throw new Exception('Statements should never be deleted at the DB level.');
    }

    Link::deleteObject($this);
    ObjectTag::deleteObject($this);
    AttachmentReference::deleteObject($this);
    Involvement::deleteFor($this);
    // a pending edit statement should not have answers, reviews or votes

    StatementExt::delete_all_by_statementId($this->id);

    parent::delete();
  }

  /**
   * Deletes the statement from the database, including its revisions. Call
   * for drafts only!
   */
  function purge() {
    $this->delete();
    Model::factory('RevisionStatement')
      ->where('id', $this->id)
      ->delete_many();
  }

  /**
   * Returns a ClaimReview object in JSON format, or null if this statement
   * should not have a structured ClaimReview tag.
   * See https://developers.google.com/search/docs/data-types/factcheck
   */
  function getClaimReviewJson() {
    // only allow claims with verdicts
    if ($this->type != self::TYPE_CLAIM ||
        $this->verdict == self::VERDICT_NONE ||
        $this->verdict == self::VERDICT_UNDECIDABLE) {
      return null;
    }

    $author = $this->getEntity();
    $authorLinks = Link::getFor($author);

    $data = [
      '@context' => 'https://schema.org',
      '@type' => 'ClaimReview',
      'datePublished' => date('Y-m-d', $this->verdictDate),
      'url' => $this->getViewUrl(true),
      'claimReviewed' => Str::shorten($this->summary, 80),

      'itemReviewed' => [
        '@type' => 'Claim',
        'author' => [
          '@type' => $author->isPerson() ? 'Person' : 'Organization',
          'name' => $author->name,
          'sameAs' => Util::objectProperty($authorLinks, 'url'),
        ],
        'datePublished' => $this->dateMade,

        'appearance' => [
          '@type' => 'OpinionNewsArticle',
          'url' => Link::getFor($this)[0]->url ?? '',
          'datePublished' => $this->dateMade,
        ],

      ],

      'author' => [
        '@type' => 'Organization',
        'name' => 'Dignitas',
        'image' => Config::URL_HOST . Config::URL_PREFIX . 'img/logo-white.svg',
        'url' => Router::link('aggregate/index', true),
      ],

      'reviewRating' => [
        '@type' => 'Rating',
        'ratingValue' => self::CR_RATINGS[$this->verdict],
        'bestRating' => self::CR_BEST_RATING,
        'worstRating' => self::CR_WORST_RATING,
        'alternateName' => $this->getVerdictName(),
      ],
    ];

    return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
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
      ->where_not_in('verdict', [ self::VERDICT_NONE, self::VERDICT_UNDECIDABLE ])
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
      ->find_array();
    $haveProof = array_column($haveProof, 'id');

    // statement-answer pairs with mismatched verdicts
    $verdictPairs = [];
    foreach (self::BAD_VERDICTS as $pair) {
      $verdictPairs[] = [ 's.verdict' => $pair[0], 'a.verdict' => $pair[1] ];
      $verdictPairs[] = [ 's.verdict' => $pair[1], 'a.verdict' => $pair[0] ];
    }
    $verdictMismatch = Model::factory('Statement')
      ->table_alias('s')
      ->select('s.id')
      ->distinct()
      ->join('answer', ['s.id', '=', 'a.statementId'], 'a')
      ->where('s.status', Ct::STATUS_ACTIVE)
      ->where('a.status', Ct::STATUS_ACTIVE)
      ->where('a.proof', true)
      ->where_any_is($verdictPairs)
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

  function __toString() {
    return Str::shorten($this->summary, 50);
  }
}
