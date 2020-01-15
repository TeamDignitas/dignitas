<?php

class HistoryRelation extends Relation {
  use HistoryTrait;

 /**
   * Returns relations that were modified in this revision.
   *
   * @param HistoryEntity $rev An entity revision
   * @param string $historyAction One of 'insert', 'update' or 'delete'
   * @return HistoryRelation[]
   */
  static function getChangesFor($rev, $historyAction) {
    return Model::factory('HistoryRelation')
      ->where('fromEntityId', $rev->id)
      ->where('historyAction', $historyAction)
      ->where('requestId', $rev->requestId)
      ->order_by_asc('rank')
      ->find_many();
  }

}
