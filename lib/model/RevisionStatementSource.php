<?php

class RevisionStatementSource extends StatementSource {
  use RevisionTrait;

 /**
   * Returns statement sources that were modified in this revision.
   *
   * @param RevisionStatement $rev A statement revision
   * @param string $revisionAction One of 'insert', 'update' or 'delete'
   * @return RevisionStatementSource[]
   */
  static function getChangesFor($rev, $revisionAction) {
    return Model::factory('RevisionStatementSource')
      ->where('statementId', $rev->id)
      ->where('revisionAction', $revisionAction)
      ->where('requestId', $rev->requestId)
      ->order_by_asc('rank')
      ->find_many();
  }

}
