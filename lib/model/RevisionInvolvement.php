<?php

class RevisionInvolvement extends Involvement {
  use RevisionTrait;

 /**
   * Returns involvements that were modified in this revision.
   *
   * @param RevisionStatement $rev A statement revision
   * @param string $revisionAction One of 'insert', 'update' or 'delete'
   * @return RevisionInvolvement[]
   */
  static function getChangesFor($rev, $revisionAction) {
    return Model::factory('RevisionInvolvement')
      ->where('statementId', $rev->id)
      ->where('revisionAction', $revisionAction)
      ->where('requestId', $rev->requestId)
      ->order_by_asc('rank')
      ->find_many();
  }

}
