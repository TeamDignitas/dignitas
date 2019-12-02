<?php

class ObjectTag extends BaseObject {
  use ObjectTypeIdTrait;

  static function create($objectType, $objectId, $tagId) {
    $ot = Model::factory('ObjectTag')->create();
    $ot->objectType = $objectType;
    $ot->objectId = $objectId;
    $ot->tagId = $tagId;
    return $ot;
  }

  // returns just the tag IDs
  static function getTagIds($object) {
    $ots = Model::factory('ObjectTag')
      ->where('objectId', $object->id)
      ->where('objectType', $object->getObjectType())
      ->order_by_asc('rank')
      ->find_many();
    return Util::objectProperty($ots, 'tagId');
  }

  // loads the actual tags, not the ObjectTags
  static function getTags($object) {
    return Model::factory('Tag')
      ->table_alias('t')
      ->select('t.*')
      ->join('object_tag', ['t.id', '=', 'ot.tagId'], 'ot')
      ->where('ot.objectId', $object->id)
      ->where('ot.objectType', $object->getObjectType())
      ->order_by_asc('ot.rank')
      ->find_many();
  }

  // Updates the list of tags for the given object. Deletes ObjectTags not
  // present in the tag list, inserts new ObjectTags where needed and updates
  // the rank field.
  // Similar, but not identical, to BaseObject::updateDependants().
  static function update($object, $tagIds) {
    $type = $object->getObjectType();

    // delete vanishing DB records
    $nonEmptyTagIds = empty($tagIds) ? [ 0 ] : $tagIds;
    Model::factory('ObjectTag')
      ->where('objectType', $type)
      ->where('objectId', $object->id)
      ->where_not_in('tagId', $nonEmptyTagIds)
      ->delete_many();

    // update or insert existing objects
    $rank = 0;
    foreach ($tagIds as $tagId) {
      $ot = ObjectTag::get_by_objectType_objectId_tagId($type, $object->id, $tagId);
      if (!$ot) {
        $ot = self::create($type, $object->id, $tagId);
      }
      $ot->rank = ++$rank;
      $ot->save();
    }
  }

}
