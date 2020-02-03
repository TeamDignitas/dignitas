<?php

class Link extends BaseObject {
  use ObjectTypeIdTrait;

  static function getFor($object) {
    return Model::factory('Link')
      ->where('objectType', $object->getObjectType())
      ->where('objectId', $object->id)
      ->order_by_asc('rank')
      ->find_many();
  }

  function getDisplayUrl() {
    return parse_url($this->url, PHP_URL_HOST);
  }

  function validUrl() {
    return filter_var($this->url, FILTER_VALIDATE_URL);
  }

  /**
   * @return bool True iff the link should be nofollow, i.e. no pagerank
   * should flow on it. This is currently true of all statement sources, since
   * we do not wish to promote potentially false statements.
   */
  function isNofollow() {
    $obj = $this->getObject();
    return $obj->getObjectType() == BaseObject::TYPE_STATEMENT;
  }

  /**
   * Builds an array of Link objects from their IDs and urls.
   */
  static function build($ids, $urls) {
    $result = [];

    foreach ($ids as $i => $id) {
      if ($urls[$i]) { // ignore empty records
        $link = $id
          ? self::get_by_id($id)
          : Model::factory('Link')->create();
        $link->url = $urls[$i];
        $result[] = $link;
      }
    }

    return $result;
  }

  /**
   * Updates the list of links for the given object. Deletes Links not present
   * in the tag list, inserts new Links where needed and updates the rank
   * field.
   *
   * Similar, but not identical, to BaseObject::updateDependants().
   */
  static function update($object, $links) {
    $type = $object->getObjectType();

    // If this is called during a clone operation for pending edits, then we
    // should be using the clones' IDs. If not, keep the same IDs.
    foreach ($links as $l) {
      $l->id = CloneMap::getNewId($object, $l);
    }

    $linkIds = array_filter(Util::objectProperty($links, 'id')); // filter out null values
    $linkIds[] = 0; // ensure non-empty set

    // delete vanishing DB records
    Model::factory('Link')
      ->where('objectType', $type)
      ->where('objectId', $object->id)
      ->where_not_in('id', $linkIds)
      ->delete_many();

    // update or insert existing objects
    $rank = 0;
    foreach ($links as $l) {
      $l->objectType = $type;
      $l->objectId = $object->id;
      $l->rank = ++$rank;
      $l->save();
    }
  }

  function __toString() {
    return $this->url;
  }
}
