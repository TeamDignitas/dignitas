<?php

class EntityType extends Proto {

  function getObjectType() {
    return Proto::TYPE_ENTITY_TYPE;
  }

  function getEditUrl() {
    return Router::link('entityType/edit') . '/' . $this->id;
  }

  /**
   * @return int The ID of the EntityType having isDefault = true, or null if
   * no such EntityType exists.
   */
  static function getDefaultId() {
    $et = self::get_by_isDefault(true);
    return $et->id ?? null;
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

  /**
   * Unsets the isDefault bit for all entity types except $exceptId.
   */
  static function clearOldDefault($exceptId) {
    $others = Model::factory('EntityType')
      ->where('isDefault', true)
      ->where_not_equal('id', $exceptId)
      ->find_many();
    foreach ($others  as $et) {
      $et->isDefault = false;
      $et->save();
    }
  }

}
