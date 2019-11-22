<?php

class Relation extends BaseObject implements DatedObject {

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

  function getToEntity() {
    return Entity::get_by_id($this->toEntityId);
  }

  /**
   * Checks if the start/end dates are meaningful for this relation type.
   *
   * @return bool
   */
  function hasDates() {
    return $this->type != self::TYPE_CLOSE_RELATIVE;
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

      if (!$this->hasDates() &&
          (($this->startDate != '0000-00-00') || ($this->endDate != '0000-00-00'))) {
        $errors[] = _('This relation type cannot have dates.');
      }

      if (($this->startDate != '0000-00-00') &&
          ($this->endDate != '0000-00-00') &&
          ($this->startDate > $this->endDate)) {
        $errors[] = _('The start date cannot be past the end date.');
      }
    }

    return $errors;
  }

}
