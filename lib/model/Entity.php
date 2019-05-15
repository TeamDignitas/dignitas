<?php

class Entity extends BaseObject implements DatedObject {

  const TYPE_PERSON = 1;
  const TYPE_PARTY = 2;
  const TYPE_UNION = 3; // of parties

  const TYPES = [
    self::TYPE_PERSON,
    self::TYPE_PARTY,
    self::TYPE_UNION,
  ];

  static function typeName($type) {
    switch ($type) {
      case self::TYPE_PERSON: return _('person');
      case self::TYPE_PARTY:  return _('party');
      case self::TYPE_UNION:  return _('union');
    }
  }

  function getTypeName() {
    return self::typeName($this->type);
  }

  function getRelations() {
    return Model::factory('Relation')
      ->where('fromEntityId', $this->id)
      ->order_by_asc('rank')
      ->find_many();
  }

  public function delete() {
    Log::warning("Deleted entity {$this->id} ({$this->name})");
    Img::deleteImages($this);
    Statement::delete_all_by_entityId($this->id);
    Relation::delete_all_by_fromEntityId($this->id);
    Relation::delete_all_by_toEntityId($this->id);
    parent::delete();
  }

  public function __toString() {
    return $this->name;
  }

}
