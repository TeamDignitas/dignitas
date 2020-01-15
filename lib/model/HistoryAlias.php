<?php

class HistoryAlias extends Alias {
  use HistoryTrait;

 /**
   * Returns aliases that were modified in this revision.
   *
   * @param HistoryEntity $rev An entity revision
   * @param string $historyAction One of 'insert', 'update' or 'delete'
   * @return HistoryAlias[]
   */
  static function getChangesFor($rev, $historyAction) {
    return Model::factory('HistoryAlias')
      ->where('entityId', $rev->id)
      ->where('historyAction', $historyAction)
      ->where('requestId', $rev->requestId)
      ->order_by_asc('rank')
      ->find_many();
  }

}
