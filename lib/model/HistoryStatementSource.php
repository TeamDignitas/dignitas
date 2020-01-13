<?php

class HistoryStatementSource extends StatementSource {
   use HistoryTrait;

 /**
   * Returns statement sources that were modified in this revision.
   *
   * @param HistoryStatement $rev A statement revision
   * @param string $historyAction One of 'insert', 'update' or 'delete'
   * @return HistoryStatementSource[]
   */
  static function getChangesFor($rev, $historyAction) {
    return Model::factory('HistoryStatementSource')
      ->where('statementId', $rev->id)
      ->where('historyAction', $historyAction)
      ->where('requestId', $rev->requestId)
      ->order_by_asc('rank')
      ->find_many();
  }

}
