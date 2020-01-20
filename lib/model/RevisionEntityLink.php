<?php

class RevisionEntityLink extends EntityLink {
  use RevisionTrait;

 /**
   * Returns links that were modified in this revision.
   *
   * @param RevisionEntity $rev An entity revision
   * @param string $revisionAction One of 'insert', 'update' or 'delete'
   * @return RevisionEntityLink[]
   */
  static function getChangesFor($rev, $revisionAction) {
    return Model::factory('RevisionEntityLink')
      ->where('entityId', $rev->id)
      ->where('revisionAction', $revisionAction)
      ->where('requestId', $rev->requestId)
      ->order_by_asc('rank')
      ->find_many();
  }

}
