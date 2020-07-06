<?php

class RelationType extends Proto {

  // How do we refer to the toEntity?
  const PHRASE_REGULAR = 0;          // associate of [Big Corporation Inc.]
  const PHRASE_LONG_POSSESSIVE = 1;  // spokesperson [of the San Francisco City Hall]
  const PHRASE_SHORT_POSSESSIVE = 2; // mayor [of San Francisco]
  const PHRASE_NONE = 3;             // prime minister

  function getObjectType() {
    return Proto::TYPE_RELATION_TYPE;
  }

  static function getPhrases() {
    return [
      self::PHRASE_REGULAR,
      self::PHRASE_LONG_POSSESSIVE,
      self::PHRASE_SHORT_POSSESSIVE,
      self::PHRASE_NONE,
    ];
  }

  static function phraseName($phrase) {
    switch ($phrase) {
      case self::PHRASE_REGULAR: return _('phrase-regular');
      case self::PHRASE_LONG_POSSESSIVE: return _('phrase-long-possessive');
      case self::PHRASE_SHORT_POSSESSIVE: return _('phrase-short-possessive');
      case self::PHRASE_NONE: return _('phrase-none');
      default: return '';
    }
  }

  function getEditUrl() {
    return Router::link('relationType/edit') . '/' . $this->id;
  }

  function getFromEntityType() {
    return EntityType::get_by_id($this->fromEntityTypeId);
  }

  function getToEntityType() {
    return EntityType::get_by_id($this->toEntityTypeId);
  }

  static function loadAll() {
    return Model::factory('RelationType')
      ->order_by_asc('rank')
      ->find_many();
  }

  /**
   * Returns the relation types that could be outgoing from this entityTypeId.
   */
  static function loadForEntityType($entityTypeId) {
    return Model::factory('RelationType')
      ->where('fromEntityTypeId', $entityTypeId)
      ->order_by_asc('rank')
      ->find_many();
  }

  /**
   * For newly created objects, assigns the next available rank. For existing
   * pages, does nothing.
   */
  function assignNewRank() {
    if (!$this->id) {
      $this->rank = 1 + Model::factory('RelationType')->count();
    }
  }

  function canDelete() {
    if (!$this->id) {
      return null; // being created, not saved yet
    }
    $relations = Relation::count_by_relationTypeId($this->id);
    return !$relations;
  }

}
