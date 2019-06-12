<?php

class Entity extends BaseObject implements DatedObject {

  const TYPE_PERSON = 1;
  const TYPE_PARTY = 2;
  const TYPE_UNION = 3; // of parties

  const DEFAULT_COLOR = '#ffffff';

  // parties and unions have colors
  const TYPES = [
    self::TYPE_PERSON => [ 'hasColor' => false ],
    self::TYPE_PARTY => [ 'hasColor' => true ],
    self::TYPE_UNION => [ 'hasColor' => true ],
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

  function getColor() {
    return $this->color ? $this->color : self::DEFAULT_COLOR;
  }

  function setColor($color) {
    $this->color = strcasecmp($color, self::DEFAULT_COLOR) ? $color : '';
  }

  function hasColor() {
    return self::TYPES[$this->type]['hasColor'];
  }

  function getAliases() {
    return Model::factory('Alias')
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

  public function delete() {
    Log::warning("Deleted entity {$this->id} ({$this->name})");
    Alias::delete_all_by_entityId($this->id);
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
