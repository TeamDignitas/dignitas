<?php

class RevisionObjectTag extends BaseObject {

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
      ->join('revision_object_tag', [ 'hot.tagId', '=', 't.id' ], 'hot')
      ->where('hot.objectType', $rev->getObjectType())
      ->where('hot.objectId', $rev->id)
      ->where('hot.revisionAction', $revisionAction)
      ->where('hot.requestId', $rev->requestId)
      ->order_by_asc('hot.rank')
      ->find_many();
  }

}
