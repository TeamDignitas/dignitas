<?php

class HistoryEntityLink extends EntityLink {
  use HistoryTrait;

 /**
   * Returns links that were modified in this revision.
   *
   * @param HistoryEntity $rev An entity revision
   * @param string $historyAction One of 'insert', 'update' or 'delete'
   * @return HistoryEntityLink[]
   */
  static function getChangesFor($rev, $historyAction) {
    return Model::factory('HistoryEntityLink')
      ->where('entityId', $rev->id)
      ->where('historyAction', $historyAction)
      ->where('requestId', $rev->requestId)
      ->order_by_asc('rank')
      ->find_many();
  }

}
