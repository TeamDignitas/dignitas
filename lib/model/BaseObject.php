<?php

class BaseObject extends Model {
  const ACTION_SELECT = 1;
  const ACTION_SELECT_ALL = 2;
  const ACTION_COUNT = 3;
  const ACTION_DELETE_ALL = 4;

  // types of objects for use in (objectType, objectId) references
  const TYPE_UNKNOWN = 0;
  const TYPE_STATEMENT = 1;
  const TYPE_ANSWER = 2;
  const TYPE_USER = 3;
  const TYPE_ENTITY = 4;
  const TYPE_RELATION = 5;
  const TYPE_COMMENT = 6;

  function __call($name, $arguments) {
    return $this->callHandler($name, $arguments);
  }

  static function __callStatic($name, $arguments) {
    return self::callHandler($name, $arguments);
  }

  // Handle calls like User::get_by_email($email) and User::get_all_by_email($email)
  static function callHandler($name, $arguments) {
    if (substr($name, 0, 7) == 'get_by_') {
      return self::action(substr($name, 7), $arguments, self::ACTION_SELECT);
    } else if (substr($name, 0, 11) == 'get_all_by_') {
      return self::action(substr($name, 11), $arguments, self::ACTION_SELECT_ALL);
    } else if (substr($name, 0, 9) == 'count_by_') {
      return self::action(substr($name, 9), $arguments, self::ACTION_COUNT);
    } else if (substr($name, 0, 14) == 'delete_all_by_') {
      self::action(substr($name, 14), $arguments, self::ACTION_DELETE_ALL);
    } else {
      self::_die('cannot handle method', $name, $arguments);
    }
  }

  private static function action($fieldString, $arguments, $action) {
    $fields = explode('_', $fieldString);
    if (count($fields) != count($arguments)) {
      self::_die('incorrect number of arguments', $action, $arguments);
    }
    $clause = Model::factory(get_called_class());
    foreach ($fields as $i => $field) {
      $clause = $clause->where($field, $arguments[$i]);
    }

    switch ($action) {
      case self::ACTION_SELECT: return $clause->find_one();
      case self::ACTION_SELECT_ALL: return $clause->find_many();
      case self::ACTION_COUNT: return $clause->count();
      case self::ACTION_DELETE_ALL:
        $objects = $clause->find_many();
        foreach ($objects as $o) {
          $o->delete();
        }
        break;
    }
  }

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
   * @return BaseObject[] An array of objects of the same class as $this.
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
   * @return int One of the BaseObject::TYPE_* values.
   */
  function getObjectType() {
    return self::TYPE_UNKNOWN;
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
   * @param BaseObject $root Root of these dependants
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

  static function _die($error, $name, $arguments) {
    printf("Error: %s in call to %s.%s, arguments: %s\n",
           $error, get_called_class(), $name, print_r($arguments, true));
    exit;
  }

}
