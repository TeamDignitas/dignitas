<?php

class Relation extends Proto {

  function getObjectType() {
    return Proto::TYPE_RELATION;
  }

  function getRelationType() {
    return RelationType::get_by_id($this->relationTypeId);
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
   * Get this Relation's temporal weight in the Markov graph, based on how
   * this duration spans the loyalty intervals.
   */
  function getTemporalWeight() {
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
    return !$this->isOpenEnded() &&
      $this->endDate < Time::today();
  }

  function isOpenEnded() {
    return $this->endDate == '0000-00-00';
  }

  /**
   * Returns -1, 0 or 1 if this relation is newer, as new or older than $other.
   */
  function newerThan($other) {
    if ($this->isOpenEnded() ^ $other->isOpenEnded()) {
      return -($this->isOpenEnded() <=> $other->isOpenEnded());
    }

    return -($this->endDate <=> $other->endDate);
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
      if (($this->getRelationType()->fromEntityTypeId != $fromEntity->entityTypeId) ||
          ($this->getRelationType()->toEntityTypeId != $toEntity->entityTypeId)) {
        $errors[] = sprintf(_('info-incorrect-relation-%s-%s-%s'),
                            $fromEntity->name, $this->getRelationType()->name, $toEntity->name);
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
                   $this->getRelationType()->name,
                   $this->getToEntity(),
                   $this->getDateRangeString());
  }

}
