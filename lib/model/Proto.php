<?php

/**
 * This class further extends Precursor which in turn extends Paris's Model
 * class. Proto adds
 *   (1) stamping with the creation date, last modification date, and the ID
 *   of the user who made the last modification;
 *   (2) access to the revision history;
 *   (3) cloning capabilities (typically for creating pending edits).
 */
class Proto extends Precursor {

  // types of objects for use in (objectType, objectId) references
  const TYPE_UNKNOWN = 0;
  const TYPE_STATEMENT = 1;
  const TYPE_ANSWER = 2;
  const TYPE_USER = 3;
  const TYPE_ENTITY = 4;
  const TYPE_RELATION = 5;
  const TYPE_COMMENT = 6;
  const TYPE_DOMAIN = 7;
  const TYPE_HELP_PAGE = 8;
  const TYPE_ENTITY_TYPE = 9;
  const TYPE_RELATION_TYPE = 10;
  const TYPE_STATIC_RESOURCE = 11;
  const TYPE_TAG = 12;

  function getModUser() {
    return User::get_by_id($this->modUserId);
  }

  /**
   * Has the object been modified after its creation?
   *
   * @return boolean
   */
  function hasRevisions() {
    return $this->createDate != $this->modDate;
  }

  /**
   * Returns the name of the revision class for $this.
   */
  function getRevisionClass() {
    return 'Revision' . get_class($this);
  }

  /**
   * Returns all the revisions of $this (newest first). The most recent
   * revision will be identical to $this.
   *
   * @return Proto[] An array of objects of the same class as $this.
   */
  function getHistory() {
    $class = $this->getRevisionClass();
    return Model::factory($class)
      ->where('id', $this->id)
      ->order_by_desc('revisionId')
      ->find_many();
  }

  /**
   * What type of object are we? Children may override this.
   *
   * @return int One of the Proto::TYPE_* values.
   */
  function getObjectType() {
    return self::TYPE_UNKNOWN;
  }

  /**
   * Returns a URL where this object can be viewed.
   */
  function getViewUrl() {
    return null;
  }

  /**
   * Returns a URL where this object can be edited.
   */
  function getEditUrl() {
    return null;
  }

  static function getObjectByTypeId($objectType, $objectId) {
    switch ($objectType) {
      case self::TYPE_STATEMENT:
        return Statement::get_by_id($objectId);
      case self::TYPE_ANSWER:
        return Answer::get_by_id($objectId);
      case self::TYPE_USER:
        return User::get_by_id($objectId);
      case self::TYPE_ENTITY:
        return Entity::get_by_id($objectId);
      case self::TYPE_RELATION:
        return Relation::get_by_id($objectId);
      case self::TYPE_COMMENT:
        return Comment::get_by_id($objectId);
      case self::TYPE_TAG:
        return Tag::get_by_id($objectId);
      default:
        return null;
    }
  }

  /**
   * Updates a list of dependants, e.g. a list of Relations for an Entity.
   * Deletes DB records not present in this list, inserts new DB records where
   * needed, and updates the rank field.
   *
   * @param object[] $objects List of objects saved by the user
   * @param Proto $root Root of these dependants
   * @param string $fkField Field to filter by in existing records or to
   * populate in new records
   * @param string $rankField Name of field holding sequential order of objects
   */
  static function updateDependants($objects, $root, $fkField, $rankField) {
    $class = get_called_class();

    // If this is called during a clone operation for pending edits, then we
    // should be using the clones' IDs. If not, keep the same IDs.
    foreach ($objects as $o) {
      $o->id = CloneMap::getNewId($root, $o);
    }

    // delete vanishing DB records
    $existingIds = array_filter(Util::objectProperty($objects, 'id'));
    $existingIds[] = 0; // ensure array is non-empty

    // We cannot call delete_many() as the $class might have its own
    // dependants which need to be deleted. For example, an entity's relations
    // each have links.
    $gone = Model::factory($class)
      ->where($fkField, $root->id)
      ->where_not_in('id', $existingIds)
      ->find_many();
    foreach ($gone as $g) {
      $g->delete();
    }

    // update or insert existing objects
    $rank = 0;
    foreach ($objects as $o) {
      $o->$fkField = $root->id;
      $o->$rankField = ++$rank;
      $o->save();
    }
  }

  /**
   * Assigns some historic fields and saves the object.
   *
   * @param int $modUserId Attribute the change to a different user than the
   * one currently logged in. Useful when merging pending edits.
   */
  function save($modUserId = null) {
    // auto-save the createDate, modDate and modUserId fields
    $this->modDate = time();
    if (!$this->createDate) {
      $this->createDate = $this->modDate;
    }
    $this->modUserId = $modUserId ?: User::getActiveId();

    return parent::save();
  }

  /**
   * Copies the values of all fields except id. Works better than PHP's clone operator.
   **/
  function parisClone() {
    $clone = Model::factory(get_called_class())->create();
    $fields = $this->as_array();
    foreach ($fields as $key => $value) {
      if ($key != 'id') {
        $clone->$key = $value;
      }
    }
    return $clone;
  }

  /**
   * Makes database copies of the object and its dependants. Called while
   * creating a pending edit. Creates CloneMap objects, except for the
   * top-level object being cloned.
   *
   * @param PendingEditTrait $root top-level clone
   * @param Map $changes Key => value changes to be made while cloning.
   */
  function deepClone($root, $changes = []) {
    $clone = $this->parisClone();
    foreach ($changes as $key => $value) {
      $clone->$key = $value;
    }
    $clone->save();

    if ($root) {
      CloneMap::create($root, $this, $clone);
    }

    return $clone;
  }

  /**
   * Copies fields from $other, excluding 'id', 'status', 'statusUserId',
   * 'userId' and any additional fields passed in $exclude.
   *
   * @param array Fields to exclude from copying.
   */
  function copyFrom($other, $exclude = []) {
    array_push($exclude, 'id', 'status', 'statusUserId', 'userId');

    $fields = $other->as_array();
    foreach ($fields as $key => $value) {
      if (!in_array($key, $exclude)) {
        $this->$key = $value;
      }
    }
  }

}
