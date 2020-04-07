<?php

class RevisionObjectTag extends Proto {

 /**
   * Returns tags that were modified in this revision.
   *
   * @param RevisionTrait $rev An object revision
   * @param string $revisionAction One of 'insert' or 'delete'
   * @return array An array of tags
   */
  static function getChangesFor($rev, $revisionAction) {
    // TODO there is no guarantee that the tag still exists
    return Model::factory('Tag')
      ->select('t.*')
      ->table_alias('t')
      ->join('revision_object_tag', [ 'rot.tagId', '=', 't.id' ], 'rot')
      ->where('rot.objectType', $rev->getObjectType())
      ->where('rot.objectId', $rev->id)
      ->where('rot.revisionAction', $revisionAction)
      ->where('rot.requestId', $rev->requestId)
      ->order_by_asc('rot.rank')
      ->find_many();
  }

}
