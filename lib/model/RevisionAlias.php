<?php

class RevisionAlias extends Alias {
  use RevisionTrait;

 /**
   * Returns aliases that were modified in this revision.
   *
   * @param RevisionEntity $rev An entity revision
   * @param string $revisionAction One of 'insert', 'update' or 'delete'
   * @return RevisionAlias[]
   */
  static function getChangesFor($rev, $revisionAction) {
    return Model::factory('RevisionAlias')
      ->where('entityId', $rev->id)
      ->where('revisionAction', $revisionAction)
      ->where('requestId', $rev->requestId)
      ->order_by_asc('rank')
      ->find_many();
  }

}
