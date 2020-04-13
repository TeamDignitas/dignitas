<?php

class RelationType extends Proto {

  function getObjectType() {
    return Proto::TYPE_RELATION_TYPE;
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
