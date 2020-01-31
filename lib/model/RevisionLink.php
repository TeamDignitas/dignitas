<?php

class RevisionLink extends Link {
  use RevisionTrait;

 /**
   * Returns links that were modified in this revision.
   *
   * @param revision $rev An object revision
   * @param string $revisionAction One of 'insert', 'update' or 'delete'
   * @return RevisionLink[]
   */
  static function getChangesFor($rev, $revisionAction) {
    return Model::factory('RevisionLink')
      ->where('objectType', $rev->getObjectType())
      ->where('objectId', $rev->id)
      ->where('revisionAction', $revisionAction)
      ->where('requestId', $rev->requestId)
      ->order_by_asc('rank')
      ->find_many();
  }

}
