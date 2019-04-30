<?php

class Relation extends BaseObject implements DatedObject {

  const TYPE_MEMBER = 1;

  const TYPES = [
    self::TYPE_MEMBER,
  ];

  static function typeName($type) {
    switch ($type) {
      case self::TYPE_MEMBER: return _('member of');
    }
  }

  function getTypeName() {
    return self::typeName($this->type);
  }

  function getToEntity() {
    return Entity::get_by_id($this->toEntityId);
  }

  // checks if an entity of type A can be a member of an entity of type B
  static function validMembership($a, $b) {
    return ($a == Entity::TYPE_PERSON && $b == Entity::TYPE_PARTY) ||
      ($a == Entity::TYPE_PARTY && $b == Entity::TYPE_UNION);
  }

}
