<?php

class Entity extends BaseObject {
  use DuplicateTrait {
    closeAsDuplicate as protected traitCloseAsDuplicate;
  }
  use FlaggableTrait, MarkdownTrait, PendingEditTrait, UploadTrait;

  const TYPE_PERSON = 1;
  const TYPE_PARTY = 2;
  const TYPE_UNION = 3; // of parties
  const TYPE_WEBSITE = 4;
  const TYPE_COMPANY = 5;

  const DEFAULT_COLOR = '#ffffff';

  // parties and unions have colors
  const TYPES = [
    self::TYPE_PERSON => [ 'hasColor' => false ],
    self::TYPE_PARTY => [ 'hasColor' => true ],
    self::TYPE_UNION => [ 'hasColor' => true ],
    self::TYPE_WEBSITE => [ 'hasColor' => false ],
    self::TYPE_COMPANY => [ 'hasColor' => false ],
  ];

  static function typeName($type) {
    switch ($type) {
      case self::TYPE_PERSON:   return _('person');
      case self::TYPE_PARTY:    return _('party');
      case self::TYPE_UNION:    return _('union');
      case self::TYPE_WEBSITE:  return _('website');
      case self::TYPE_COMPANY:  return _('company');
    }
  }

  function getObjectType() {
    return BaseObject::TYPE_ENTITY;
  }

  function getTypeName() {
    return self::typeName($this->type);
  }

  private function getFileSubdirectory() {
    return 'entity';
  }

  private function getFileRoute() {
    return 'entity/image';
  }

  function getMarkdownFields() {
    return [ 'profile' ];
  }

  function getColor() {
    return $this->color ? $this->color : self::DEFAULT_COLOR;
  }

  function setColor($color) {
    $this->color = strcasecmp($color, self::DEFAULT_COLOR) ? $color : '';
  }

  function hasColor() {
    return self::TYPES[$this->type]['hasColor'] ?? false;
  }

  function getTags() {
    return ObjectTag::getTags($this);
  }

  /**
   * Returns human-readable information about the status of this Entity.
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

    $rec['status'] = $dup ? _('duplicate') : _('closed');

    $rec['dup'] = $dup;

    $rec['cssClass'] = ($this->status == Ct::STATUS_DELETED)
      ? 'alert-danger'
      : 'alert-warning';

    $rec['details'] = ($this->status == Ct::STATUS_CLOSED)
      ? _('This entity was closed')
      : _('This entity was deleted');

    switch ($this->reason) {
      case Ct::REASON_SPAM: $r = _('because its profile contains spam.'); break;
      case Ct::REASON_ABUSE: $r = _('because its profile contains insults or abuse.'); break;
      case Ct::REASON_DUPLICATE: $r = _('as a duplicate of the entity'); break;
      case Ct::REASON_OFF_TOPIC: $r = _('because it is off-topic.'); break;
      case Ct::REASON_BY_OWNER: $r = _('by the user who added it.'); break;
      case Ct::REASON_BY_USER: $r = _('by'); break;
      case Ct::REASON_OTHER: $r = _('for other reasons.'); break;
      default: $r = '';
    }
    $rec['details'] .= ' ' . $r;

    return $rec;
  }

  function getAliases() {
    return Model::factory('Alias')
      ->where('entityId', $this->id)
      ->order_by_asc('rank')
      ->find_many();
  }

  function getLinks() {
    return Model::factory('EntityLink')
      ->where('entityId', $this->id)
      ->order_by_asc('rank')
      ->find_many();
  }

  function getRelations() {
    return Model::factory('Relation')
      ->where('fromEntityId', $this->id)
      ->order_by_asc('rank')
      ->find_many();
  }

  /**
   * Returns a list of the entity's statements visible to the active user.
   *
   * @return Statement[]
   */
  function getStatements($limit = 10) {
    $st = Model::factory('Statement')
      ->where('entityId', $this->id)
      ->where_not_equal('status', Ct::STATUS_PENDING_EDIT);

    if (!User::may(User::PRIV_DELETE_STATEMENT)) {
      // keep in sync with Statement::isDeletable();
      $st = $st->where_raw('(status != ? or userId = ?)',
                           [ Ct::STATUS_DELETED, User::getActiveId() ]);
    }

    return $st
      ->order_by_desc('createDate')
      ->limit($limit)
      ->find_many();
  }

  function getMembers() {
    return Model::factory('Entity')
      ->table_alias('m')
      ->select('m.*')
      ->distinct()
      ->join('relation', ['m.id', '=', 'r.fromEntityId'], 'r')
      ->where('r.toEntityId', $this->id)
      ->where('r.type', Relation::TYPE_MEMBER)
      // where_any_is does not work with null values
      ->where_raw('((r.startDate is null) or (r.startDate <= ?))', [ Time::today() ])
      ->where_raw('((r.endDate is null) or (r.endDate >= ?))', [ Time::today() ])
      ->find_many();
  }

  // returns a map of Entity (party) => fraction, where the fractions add up to 1.
  function getLoyalty() {
    // entityId (party ID) => sum of coefficients
    $map = [];

    foreach ($this->getRelations() as $rel) {
      $relStartDays = Time::daysAgo($rel->startDate) ?? 10000;
      $relEndDays = Time::daysAgo($rel->endDate) ?? 0;
      // now intersect this interval with each predefined loyalty interval

      $prevBoundary = 0; // today
      foreach (Config::LOYALTY_INTERVALS as $rec) {
        list ($boundary, $score) = $rec;

        // invariant: $relEndDays < $relStartDays and $prevBoundary < $prevBoundary
        $left = max($relEndDays, $prevBoundary);
        $right = min($relStartDays, $boundary);

        if ($left < $right) {
          $existing = $map[$rel->toEntityId] ?? 0;
          $map[$rel->toEntityId] = $existing + ($right - $left) * $score;
        }

        $prevBoundary = $boundary;
      }
    }

    // normalization
    if (!empty($map)) {
      $sum = array_sum($map);
      foreach ($map as $k => &$value) {
        $value /= $sum;
      }
      arsort($map);
    }

    // load the entities for the given IDs
    $results = [];
    foreach ($map as $entityId => &$value) {
      $results[] = [
        'entity' => Entity::get_by_id($entityId),
        'value' => $value,
      ];
    }

    return $results;
  }

  function isViewable() {
    return
      ($this->status != Ct::STATUS_PENDING_EDIT) &&
      (($this->status != Ct::STATUS_DELETED) ||
       User::may(User::PRIV_DELETE_ENTITY));
  }

  protected function isEditableCore() {
    if (!$this->id && !User::may(User::PRIV_ADD_ENTITY)) {
      throw new Exception(sprintf(
        _('You need at least %s reputation to add entities.'),
        Str::formatNumber(User::PRIV_ADD_ENTITY)));
    }

    if ($this->id &&
        !User::may(User::PRIV_EDIT_ENTITY) &&     // can edit any entities
        $this->userId != User::getActiveId()) {   // can always edit user's own entities
      throw new Exception(sprintf(
        _('You need at least %s reputation to edit entities.'),
        Str::formatNumber(User::PRIV_EDIT_ENTITY)));
    }
  }

  /**
   * Checks whether the active user may delete this entity.
   *
   * @return boolean
   */
  function isDeletable() {
    $numStatements = Model::factory('Statement')
      ->where('entityId', $this->id)
      ->where_not_equal('status', Ct::STATUS_DELETED)
      ->count();

    return
      !$numStatements &&                       // no publicly visible statements
      $this->id &&                             // not on the add entity page
      $this->status == Ct::STATUS_ACTIVE &&
      (User::may(User::PRIV_DELETE_ENTITY) ||  // can delete any entity
       $this->userId == User::getActiveId());  // can always delete user's own entities
  }

  /**
   * Closes the Entity as a duplicate.
   */
  function closeAsDuplicate($duplicateId) {
    $this->traitCloseAsDuplicate($duplicateId);

    // transfer statements to $duplicateId
    $statements = Statement::get_all_by_entityId($this->id);
    foreach ($statements as $s) {
      $s->entityId = $duplicateId;
      $s->save();
    }
  }

  function deepClone($root = null, $changes = []) {
    $clone = parent::deepClone($root, $changes);
    foreach ($this->getAliases() as $a) {
      $a->deepClone($clone, [ 'entityId' => $clone->id]);
    }
    foreach ($this->getRelations() as $r) {
      $r->deepClone($clone, [ 'fromEntityId' => $clone->id]);
    }
    foreach ($this->getLinks() as $l) {
      $l->deepClone($clone, [ 'entityId' => $clone->id]);
    }
    foreach (ObjectTag::getObjectTags($this) as $ot) {
      $ot->deepClone($clone, [ 'objectId' => $clone->id]);
    }

    // copy the entity image if one exists
    $clone->copyUploadedFileFrom($this);

    return $clone;
  }

  protected function deepMerge($other) {
    $this->copyFrom($other);
    $this->save($other->modUserId);

    $this->mergeDependants(
      $other, $this->getAliases(), $other->getAliases(), 'entityId');
    $this->mergeDependants(
      $other, $this->getRelations(), $other->getRelations(), 'fromEntityId');
    $this->mergeDependants(
      $other, $this->getLinks(), $other->getLinks(), 'entityId');
    $this->mergeDependants(
      $other, ObjectTag::getObjectTags($this), ObjectTag::getObjectTags($other), 'objectId');

    // a pending edit entity should not have statements, reviews or votes

    $this->copyUploadedFileFrom($other);
  }

  function delete() {
    if ($this->status != Ct::STATUS_PENDING_EDIT) {
      throw new Exception(
        "Entities should never be deleted at the DB level.");
    }

    Alias::delete_all_by_entityId($this->id);
    Relation::delete_all_by_fromEntityId($this->id);
    Relation::delete_all_by_toEntityId($this->id);
    EntityLink::delete_all_by_entityId($this->id);
    AttachmentReference::deleteObject($this);
    ObjectTag::deleteObject($this);
    // a pending edit entity should not have statements, reviews or votes

    $this->deleteFiles();

    parent::delete();
  }

  function __toString() {
    return $this->name;
  }

}
