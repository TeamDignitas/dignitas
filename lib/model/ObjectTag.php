<?php

class ObjectTag extends BaseObject implements DatedObject {

  const TYPE_STATEMENT = 1;
  const TYPE_ANSWER = 2;
  const TYPE_ENTITY = 3;

  static function getAllByIdType($objectId, $objectType) {
    return Model::factory('ObjectTag')
      ->where('objectId', $objectId)
      ->where('objectType', $objectType)
      ->order_by_asc('id')
      ->find_many();
  }

  static function getStatementTags($statementId) {
    return self::getAllByIdType($statementId, self::TYPE_STATEMENT);
  }

  static function getAnswerTags($answerId) {
    return self::getAllByIdType($answerId, self::TYPE_ANSWER);
  }

  static function getEntityTags($entityId) {
    return self::getAllByIdType($entityId, self::TYPE_ENTITY);
  }

  // loads the actual tags, not the ObjectTags
  static function getTags($objectId, $objectType) {
    return Model::factory('Tag')
      ->table_alias('t')
      ->select('t.*')
      ->join('ObjectTag', ['t.id', '=', 'ot.tagId'], 'ot')
      ->where('ot.objectId', $objectId)
      ->where('ot.objectType', $objectType)
      ->order_by_asc('ot.id')
      ->find_many();
  }

  static function associate($objectType, $objectId, $tagId) {
    // The association should not already exist
    if (!self::get_by_objectType_objectId_tagId($objectType, $objectId, $tagId)) {
      $ot = Model::factory('ObjectTag')->create();
      $ot->objectType = $objectType;
      $ot->objectId = $objectId;
      $ot->tagId = $tagId;
      $ot->save();
    }
  }

  static function dissociate($objectType, $objectId, $tagId) {
    ObjectTag::delete_all_by_objectType_objectId_tagId($objectType, $objectId, $tagId);
  }

}
