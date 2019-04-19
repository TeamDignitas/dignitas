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

}
