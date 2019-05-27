<?php

class ObjectTag extends BaseObject implements DatedObject {

  const TYPE_STATEMENT = 1;
  const TYPE_ANSWER = 2;
  const TYPE_ENTITY = 3;

  static function create($objectType, $objectId, $tagId) {
    $ot = Model::factory('ObjectTag')->create();
    $ot->objectType = $objectType;
    $ot->objectId = $objectId;
    $ot->tagId = $tagId;
    return $ot;
  }

  static function getAllByTypeId($objectType, $objectId) {
    return Model::factory('ObjectTag')
      ->where('objectId', $objectId)
      ->where('objectType', $objectType)
      ->order_by_asc('rank')
      ->find_many();
  }

  static function getStatementTags($statementId) {
    return self::getAllByTypeId(self::TYPE_STATEMENT, $statementId);
  }

  static function getAnswerTags($answerId) {
    return self::getAllByTypeId(self::TYPE_ANSWER, $answerId);
  }

  static function getEntityTags($entityId) {
    return self::getAllByTypeId(self::TYPE_ENTITY, $entityId);
  }

  // returns just the tag IDs
  static function getTagIds($objectType, $objectId) {
    $tagObjects = self::getAllByTypeId($objectType, $objectId);
    return Util::objectProperty($tagObjects, 'tagId');
  }

  // loads the actual tags, not the ObjectTags
  static function getTags($objectType, $objectId) {
    return Model::factory('Tag')
      ->table_alias('t')
      ->select('t.*')
      ->join('object_tag', ['t.id', '=', 'ot.tagId'], 'ot')
      ->where('ot.objectId', $objectId)
      ->where('ot.objectType', $objectType)
      ->order_by_asc('ot.rank')
      ->find_many();
  }

  // Updates the list of tags for the given object. Deletes ObjectTags not
  // present in the tag list, inserts new ObjectTags where needed and updates
  // the rank field.
  // Similar, but not identical, to BaseObject::updateDependants().
  static function update($objectType, $objectId, $tagIds) {
    // delete vanishing DB records
    $nonEmptyTagIds = empty($tagIds) ? [ 0 ] : $tagIds;
    Model::factory('ObjectTag')
      ->where('objectType', $objectType)
      ->where('objectId', $objectId)
      ->where_not_in('tagId', $nonEmptyTagIds)
      ->delete_many();

    // update or insert existing objects
    $rank = 0;
    foreach ($tagIds as $tagId) {
      $ot = ObjectTag::get_by_objectType_objectId_tagId($objectType, $objectId, $tagId);
      if (!$ot) {
        $ot = self::create($objectType, $objectId, $tagId);
      }
      $ot->rank = ++$rank;
      $ot->save();
    }
  }

}
