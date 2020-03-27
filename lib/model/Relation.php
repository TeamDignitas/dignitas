<?php

class Relation extends Proto {

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
      case self::TYPE_MEMBER:           return _('relation-type-member');
      case self::TYPE_ASSOCIATE:        return _('relation-type-associate');
      case self::TYPE_CLOSE_RELATIVE:   return _('relation-type-close-relative');
      case self::TYPE_DISTANT_RELATIVE: return _('relation-type-distant-relative');
    }
  }

  function getTypeName() {
    return self::typeName($this->type);
  }

  function getObjectType() {
    return Proto::TYPE_RELATION;
  }

  function getFromEntity() {
    return Entity::get_by_id($this->fromEntityId);
  }

  function getToEntity() {
    return Entity::get_by_id($this->toEntityId);
  }

  function getLinks() {
    return Link::getFor($this);
  }

  function getDateRangeString() {
    $sd = Time::localDate($this->startDate);
    $ed = Time::localDate($this->endDate);

    if ($sd && $ed) {
      return "({$sd} – {$ed})";
    } else if ($sd) {
      return sprintf('(%s %s)', _('since'), $sd);
    } else if ($ed) {
      return sprintf('(%s %s)', _('until'), $ed);
    } else {
      return '';
    }

  }

  /**
   * Get this Relation's weight in the Markov graph.
   */
  function getWeight() {
    // Conceptually reverse the axis so the past is to the right. Do this
    // because everything is expressed in days ago, so "further ago" means
    // larger numbers.
    $leftRel = Time::daysAgo($this->endDate) ?? 0;
    $rightRel = Time::daysAgo($this->startDate) ?? 10000;
    $result = 0.0;

    // now intersect this interval with each predefined loyalty interval
    foreach (Config::LOYALTY_INTERVALS as list($from, $to, $points)) {
      $left = max($from, $leftRel);
      $right = min($to, $rightRel);

      if ($left <= $right) {
        $result += ($right - $left + 1) * $points;
      }
    }

    return $result;
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
      $errors[] = _('info-target-entity');
    } else if ($fromEntity->id == $this->toEntityId) {
      $errors[] = _('info-related-itself');
    } else {
      // there exists a distinct $toEntity
      $list = self::VALID_TYPES[$fromEntity->type][$this->type] ?? [];
      if (!in_array($toEntity->type, $list)) {
        $errors[] = _('info-incorrect-relation-type');
      }

      if (($this->startDate != '0000-00-00') &&
          ($this->endDate != '0000-00-00') &&
          ($this->startDate > $this->endDate)) {
        $errors[] = _('info-dates-out-of-order');
      }
    }

    return $errors;
  }

  function delete() {
    Link::deleteObject($this);
    parent::delete();
  }

  function __toString() {
    return sprintf('%s %s %s',
                   $this->getTypeName(),
                   $this->getToEntity(),
                   $this->getDateRangeString());
  }

}
