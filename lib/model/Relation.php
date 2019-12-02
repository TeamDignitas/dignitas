<?php

class Relation extends BaseObject {

  const TYPE_MEMBER = 1;
  const TYPE_ASSOCIATE = 2;
  const TYPE_CLOSE_RELATIVE = 3;
  const TYPE_DISTANT_RELATIVE = 4;

  const TYPES = [
    self::TYPE_MEMBER,
    self::TYPE_ASSOCIATE,
    self::TYPE_CLOSE_RELATIVE,
    self::TYPE_DISTANT_RELATIVE,
  ];

  /**
   * Complete list of valid (Entity, Relation, Entity) scenarios.
   */
  const VALID_TYPES = [
    Entity::TYPE_PERSON => [
      self::TYPE_MEMBER => [ Entity::TYPE_PARTY ],
      self::TYPE_ASSOCIATE => [ Entity::TYPE_COMPANY ],
      self::TYPE_CLOSE_RELATIVE => [ Entity::TYPE_PERSON ],
      self::TYPE_DISTANT_RELATIVE => [ Entity::TYPE_PERSON ],
    ],
    Entity::TYPE_PARTY => [
      self::TYPE_MEMBER => [ Entity::TYPE_UNION ],
    ],
  ];

  static function typeName($type) {
    switch ($type) {
      case self::TYPE_MEMBER:           return _('member of');
      case self::TYPE_ASSOCIATE:        return _('associate in');
      case self::TYPE_CLOSE_RELATIVE:   return _('close relative of');
      case self::TYPE_DISTANT_RELATIVE: return _('distant relative of');
    }
  }

  function getTypeName() {
    return self::typeName($this->type);
  }

  function getFromEntity() {
    return Entity::get_by_id($this->fromEntityId);
  }

  function getToEntity() {
    return Entity::get_by_id($this->toEntityId);
  }

  function getSources() {
    return Model::factory('RelationSource')
      ->where('relationId', $this->id)
      ->order_by_asc('rank')
      ->find_many();
  }

  /**
   * Checks if this Relation's end date is in the past.
   *
   * @return bool
   */
  function ended() {
    return $this->endDate != '0000-00-00' &&
      $this->endDate < Time::today();
  }

  /**
   * Validates this Relation. The entity for fromEntityId is already loaded
   * and known to exist. Returns an array of error messages.
   *
   * @param $fromEntity Entity for $this->fromEntityId
   * @return string[]
   */
  function validate($fromEntity) {
    $errors = [];
    $toEntity = $this->getToEntity();

    if (!$toEntity) {
      $errors[] = _('Please choose a target entity.');
    } else if ($fromEntity->id == $this->toEntityId) {
      $errors[] = _('An entity cannot be related to itself.');
    } else {
      // there exists a distinct $toEntity
      $list = self::VALID_TYPES[$fromEntity->type][$this->type] ?? [];
      if (!in_array($toEntity->type, $list)) {
        $errors[] = _('Incorrect relation type.');
      }

      if (($this->startDate != '0000-00-00') &&
          ($this->endDate != '0000-00-00') &&
          ($this->startDate > $this->endDate)) {
        $errors[] = _('The start date cannot be past the end date.');
      }
    }

    return $errors;
  }

  function delete() {
    RelationSource::delete_all_by_relationId($this->id);
    parent::delete();
  }

}
