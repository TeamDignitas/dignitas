<?php

class Entity extends Proto {
  use DuplicateTrait {
    closeAsDuplicate as protected traitCloseAsDuplicate;
  }
  use ArchivableLinksTrait,
    FlaggableTrait,
    MarkdownTrait,
    PendingEditTrait,
    UploadTrait;

  const DEFAULT_COLOR = '#ffffff';

  const PROFILE_MAX_LENGTH = 4000;

  function getObjectType() {
    return Proto::TYPE_ENTITY;
  }

  function getViewUrl() {
    // For SEO purposes we also output a URL-friendly entity name.
    $name = str_replace([' ', '.', ','], ['-', '', ''], $this->name);
    $name = strtolower(Str::flatten($name));
    return sprintf('%s/%d/%s',
                   Router::link('entity/view'), $this->id, $name);
  }

  function getEditUrl() {
    return Router::link('entity/edit') . '/' . $this->id;
  }

  function getHistoryUrl() {
    return Router::link('entity/history') . '/' . $this->id;
  }

  function getArchivableUrls() {
    if (in_array($this->status, [ Ct::STATUS_ACTIVE, Ct::STATUS_CLOSED ])) {
      return self::extractArchivableUrls($this->profile);
    } else {
      return [];
    }
  }

  function getUser() {
    return User::get_by_id($this->userId);
  }

  /**
   * @param $form This entity's long or short possessive form as dictated by a
   * relationship's phrase.
   *
   * @return An HTML <a> element.
   */
  function getPossessiveHyperlink($form) {
    if (!$form) {
      // fallback to the regular (nominative) phrase
      return $this->getHyperlink(RelationType::PHRASE_REGULAR);
    }
    if (!preg_match('/(.*)\[(.*)\](.*)/', $form, $matches)) {
      // return plain text because there is no [hyperlink] pattern
      return $form;
    }

    return sprintf('%s<a href="%s">%s</a>%s',
                   htmlspecialchars($matches[1]),
                   $this->getViewUrl(),
                   htmlspecialchars($matches[2]),
                   htmlspecialchars($matches[3]));
  }

  /**
   * @return An HTML <a> element according to the relationship phrase type.
   */
  function getHyperlink($phrase = RelationType::PHRASE_REGULAR) {
    switch ($phrase) {
      case RelationType::PHRASE_REGULAR:
        $n = htmlspecialchars($this->name);
        return sprintf('<a href="%s">%s</a>', $this->getViewUrl(), $n);
      case RelationType::PHRASE_LONG_POSSESSIVE:
        return $this->getPossessiveHyperlink($this->longPossessive);
      case RelationType::PHRASE_SHORT_POSSESSIVE:
        return $this->getPossessiveHyperlink($this->shortPossessive);
      case RelationType::PHRASE_NONE:
        return '';
    }
  }

  function getEntityType() {
    return EntityType::get_by_id($this->entityTypeId);
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

  function getRegion() {
    return Region::get_by_id($this->regionId);
  }

  function getColor() {
    return $this->color ?: self::DEFAULT_COLOR;
  }

  function setColor($color) {
    $this->color = strcasecmp($color, self::DEFAULT_COLOR) ? $color : '';
  }

  function hasColor() {
    return $this->getEntityType()->hasColor ?? false;
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

    $rec['status'] = $dup
      ? _('status-entity-duplicate')
      : ($this->status == Ct::STATUS_CLOSED
         ? _('status-entity-closed')
         : _('status-entity-deleted'));

    $rec['dup'] = $dup;

    $rec['details'] = ($this->status == Ct::STATUS_CLOSED)
      ? _('info-entity-closed')
      : _('info-entity-deleted');

    switch ($this->reason) {
      case Ct::REASON_SPAM: $r = _('info-entity-spam'); break;
      case Ct::REASON_ABUSE: $r = _('info-entity-abuse'); break;
      case Ct::REASON_DUPLICATE: $r = _('info-entity-duplicate-of'); break;
      case Ct::REASON_OFF_TOPIC: $r = _('info-entity-off-topic'); break;
      case Ct::REASON_UNVERIFIABLE: $r = _('info-entity-unverifiable'); break;
      case Ct::REASON_BY_OWNER: $r = _('info-entity-by-owner'); break;
      case Ct::REASON_BY_USER: $r = _('info-by'); break;
      case Ct::REASON_OTHER: $r = _('info-other-reason'); break;
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
    return Link::getFor($this);
  }

  function getRelations() {
    return Model::factory('Relation')
      ->where('fromEntityId', $this->id)
      ->order_by_asc('rank')
      ->find_many();
  }

  /**
   * Returns a query that loads or counts this entity's statements visible to
   * the current user.
   *
   * @return ORMWrapper
   */
  function getStatementQuery() {
    $query = Model::factory('Statement')
      ->where('entityId', $this->id);

    return Statement::filterViewable($query);
  }

  /**
   * Returns a query that loads or counts statements involving this entity
   * that are visible to the active user.
   *
   * @return ORMWrapper
   */
  function getInvolvementQuery() {
    $query = Model::factory('Statement')
      ->select('s.*')
      ->table_alias('s')
      ->join('involvement', ['s.id', '=', 'i.statementId'], 'i')
      ->where('i.entityId', $this->id);

    return Statement::filterViewable($query);
  }

  function getMembers() {
    return Model::factory('Entity')
      ->table_alias('m')
      ->select('m.*')
      ->distinct()
      ->join('relation', ['m.id', '=', 'r.fromEntityId'], 'r')
      ->join('relation_type', ['r.relationTypeId', '=', 'rt.id'], 'rt')
      ->where('r.toEntityId', $this->id)
      ->where('rt.membership', true)
      // where_any_is does not work with null values
      ->where_raw('((r.startDate is null) or (r.startDate <= ?))', [ Time::today() ])
      ->where_raw('((r.endDate is null) or (r.endDate >= ?))', [ Time::today() ])
      ->find_many();
  }

  function isPerson() {
    return $this->entityTypeId == Config::PERSON_ENTITY_TYPE_ID;
  }

  function hasLoyalties() {
    return Loyalty::count_by_fromEntityId($this->id) > 0;
  }

  /**
   * @return Entity[] An array of entities augmented with a value field.
   */
  function getLoyalties() {
    return Model::factory('Entity')
      ->table_alias('e')
      ->select('e.*')
      ->select('l.value')
      ->join('loyalty', ['e.id', '=', 'l.toEntityId'], 'l')
      ->where('l.fromEntityId', $this->id)
      ->order_by_desc('l.value')
      ->find_many();
  }

  /**
   * Tries to find the entity that made the statement at $url.
   * Currently only handles Facebook posts.
   *
   * @return int The entity's ID or null if there is no match.
   */
  static function getFromStatementUrl($url) {
    if (preg_match('#(^https://www.facebook.com/[^/]+)/#', $url, $matches)) {
      $entityUrl = $matches[1];

      // see if we have an entity with this Facebook page
      $l = Model::factory('Link')
        ->where('objectType', Proto::TYPE_ENTITY)
        ->where_in('url', [ $entityUrl, $entityUrl . '/' ])
        ->find_one();
      return $l->objectId ?? null;
    }
    return null;
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
        _('info-minimum-reputation-add-entity-%s'),
        Str::formatNumber(User::PRIV_ADD_ENTITY)));
    }

    if ($this->id &&
        !User::may(User::PRIV_EDIT_ENTITY) &&     // can edit any entities
        $this->userId != User::getActiveId()) {   // can always edit user's own entities
      throw new Exception(sprintf(
        _('info-minimum-reputation-edit-entity-%s'),
        Str::formatNumber(User::PRIV_EDIT_ENTITY)));
    }

    if (!$this->id && Ban::exists(Ban::TYPE_ADD_ENTITY)) {
      throw new Exception(_('info-banned-add-entity'));
    }

    if ($this->id && Ban::exists(Ban::TYPE_EDIT_ENTITY)) {
      throw new Exception(_('info-banned-edit-entity'));
    }
  }


  /**
   * Checks whether the active user may add statements to this entity.
   *
   * @return boolean
   */
  function acceptsNewStatements() {
    return
      User::may(User::PRIV_ADD_STATEMENT) &&
      ($this->status == Ct::STATUS_ACTIVE);
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
      in_array($this->status, [ Ct::STATUS_ACTIVE, Ct::STATUS_CLOSED ]) &&
      !Ban::exists(Ban::TYPE_DELETE) &&
      (User::may(User::PRIV_DELETE_ENTITY) ||  // can delete any entity
       $this->userId == User::getActiveId());  // can always delete user's own entities
  }

  /**
   * Checks whether the active user may reopen this entity.
   *
   * @return boolean
   */
  function isReopenable() {
    return
      in_array($this->status, [ Ct::STATUS_CLOSED, Ct::STATUS_DELETED ]) &&
      User::isModerator();
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
      $l->deepClone($clone, [ 'objectId' => $clone->id]);
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
      $other, $this->getLinks(), $other->getLinks(), 'objectId');
    $this->mergeDependants(
      $other, ObjectTag::getObjectTags($this), ObjectTag::getObjectTags($other), 'objectId');

    // a pending edit entity should not have statements, reviews or votes

    $this->copyUploadedFileFrom($other);
  }

  /**
   * Subscribes the author of the most recent change to this entity. Call
   * after saving the entity.
   */
  function subscribe() {
    if ($this->status != Ct::STATUS_PENDING_EDIT) {
      // entities don't have votes, answers or comments, so only subscribe to changes
      Subscription::subscribe($this, $this->modUserId, Notification::TYPE_CHANGES);
    }
  }

  function notify(int $type = Notification::TYPE_CHANGES) {
    if ($this->status != Ct::STATUS_PENDING_EDIT) {
      Notification::notify($this, $type);
    }
  }

  function delete() {
    if ($this->status != Ct::STATUS_PENDING_EDIT) {
      throw new Exception(
        "Entities should never be deleted at the DB level.");
    }

    Alias::delete_all_by_entityId($this->id);
    Relation::delete_all_by_fromEntityId($this->id);
    Relation::delete_all_by_toEntityId($this->id);
    Link::deleteObject($this);
    AttachmentReference::deleteObject($this);
    ObjectTag::deleteObject($this);
    // a pending edit entity should not have statements, reviews, votes or loyalties

    $this->deleteFiles();

    parent::delete();
  }

  function __toString() {
    return $this->name;
  }

}
