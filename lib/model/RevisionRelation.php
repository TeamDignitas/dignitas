<?php

class RevisionRelation extends Relation {
  use RevisionTrait;

 /**
   * Returns relations that were modified in this revision.
   *
   * @param RevisionEntity $rev An entity revision
   * @param string $revisionAction One of 'insert', 'update' or 'delete'
   * @return RevisionRelation[]
   */
  static function getChangesFor($rev, $revisionAction) {
    return Model::factory('RevisionRelation')
      ->where('fromEntityId', $rev->id)
      ->where('revisionAction', $revisionAction)
      ->where('requestId', $rev->requestId)
      ->order_by_asc('rank')
      ->find_many();
  }

}
