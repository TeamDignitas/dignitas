<?php

class HistoryObjectTag extends BaseObject {

 /**
   * Returns tags that were modified in this revision.
   *
   * @param HistoryTrait $rev An object revision
   * @param string $historyAction One of 'insert' or 'delete'
   * @return array An array of tags
   */
  static function getChangesFor($rev, $historyAction) {
    // TODO there is no guarantee that the tag still exists
    return Model::factory('Tag')
      ->select('t.*')
      ->table_alias('t')
      ->join('history_object_tag', [ 'hot.tagId', '=', 't.id' ], 'hot')
      ->where('hot.objectType', $rev->getObjectType())
      ->where('hot.objectId', $rev->id)
      ->where('hot.historyAction', $historyAction)
      ->where('hot.requestId', $rev->requestId)
      ->order_by_asc('hot.rank')
      ->find_many();
  }

}
