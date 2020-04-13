<?php

class EntityType extends Proto {

  function getObjectType() {
    return Proto::TYPE_ENTITY_TYPE;
  }

  static function loadAll() {
    return Model::factory('EntityType')
      ->order_by_asc('name')
      ->find_many();
  }

  function canDelete() {
    if (!$this->id) {
      return null; // being created, not saved yet
    }
    $entities = Entity::count_by_entityTypeId($this->id);
    $outgoing = RelationType::count_by_fromEntityTypeId($this->id);
    $incoming = RelationType::count_by_toEntityTypeId($this->id);
    return !$entities && !$outgoing && !$incoming;
  }

}
