<?php

/**
 * A many-to-many mapping of statements to entities involved with those
 * statements (for example, entities whom the statements refer to).
 */
class Involvement extends Proto {

  static function create($statementId, $entityId) {
    $inv = Model::factory('Involvement')->create();
    $inv->statementId = $statementId;
    $inv->entityId = $entityId;
    return $inv;
  }

  static function getFor(Statement $statement) {
    return Model::factory('Involvement')
      ->where('statementId', $statement->id)
      ->order_by_asc('rank')
      ->find_many();
  }

  static function getEntityIds(Statement $statement) {
    $involvements = self::getFor($statement);
    return Util::objectProperty($involvements, 'entityId');
  }

  static function deleteFor(Statement $statement) {
    self::delete_all_by_statementId($statement->id);
  }

  function __toString() {
    $entity = Entity::get_by_id($this->entityId);
    return $entity->name;
  }
}
