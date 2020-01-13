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
  function hasVersions() {
    return $this->createDate != $this->modDate;
  }

  /**
   * Returns the name of the history class for $this.
   */
  function getHistoryClass() {
    return 'History' . get_class($this);
  }

  /**
   * Returns all the revisions of $this (newest first). The most recent
   * version will be identical to $this.
   *
   * @return BaseObject[] An array of objects of the same class as $this.
   */
  function getHistory() {
    $class = $this->getHistoryClass();
    return Model::factory($class)
      ->where('id', $this->id)
      ->order_by_desc('historyId')
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
      case self::TYPE_ENTITY:
        return Entity::get_by_id($objectId);
      default:
        return null;
    }
  }

  /**
   * Updates a list of dependants, e.g. a list of Relations for an Entity.
   * Deletes DB records not present in this list, inserts new DB records where
   * needed, and updates the rank field.
   *
   * @param object[] $objects List of objects saved by the user.
   * @param string $fkField Field to filter by in existing records or to
   * populate in new records.
   * @param any $fkValue Value of $fkField.
   * @param string $rankField Name of field holding sequential order of objects.
   * @param Map $refs Map of old ID => new ID to be used during cloning (for
   * pending edits).
   */
  static function updateDependants($objects, $fkField, $fkValue, $rankField, $refs) {
    $class = get_called_class();

    // use new IDs if available
    foreach ($objects as $o) {
      $o->id = $refs[$class][$o->id] ?? $o->id;
    }

    // delete vanishing DB records
    $existingIds = array_filter(Util::objectProperty($objects, 'id'));
    $existingIds[] = 0; // ensure array is non-empty

    // We cannot call delete_many() as the $class might have its own
    // dependants which need to be deleted. For example, an entity's relations
    // each have sources.
    $gone = Model::factory($class)
      ->where($fkField, $fkValue)
      ->where_not_in('id', $existingIds)
      ->find_many();
    foreach ($gone as $g) {
      $g->delete();
    }

    // update or insert existing objects
    $rank = 0;
    foreach ($objects as $o) {
      $o->$fkField = $fkValue;
      $o->$rankField = ++$rank;
      $o->save();
    }
  }

  function save() {
    // auto-save the createDate, modDate and modUserId fields
    $this->modDate = time();
    if (!$this->createDate) {
      $this->createDate = $this->modDate;
    }
    $this->modUserId = User::getActiveId();

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
   * Makes a DB copy of the object and makes a note of the old and new ID.
   *
   * @param Map $refs Collects the old ID => new ID map per object type.
   * @param Map $changes Key => value changes to be made while cloning.
   */
  function deepClone(&$refs, $changes = []) {
    if (!$this->id) {
      return null;
    }

    $clone = $this->parisClone();
    foreach ($changes as $key => $value) {
      $clone->$key = $value;
    }
    $clone->save();

    $refs[get_called_class()][$this->id] = $clone->id;

    return $clone;
  }

  /**
   * Copies fields from $other, excluding 'id', 'status', 'statusUserId',
   * 'userId' and any additional fields passed in $exclude. Saves $this at the
   * end.
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
    $this->save();
  }

  static function _die($error, $name, $arguments) {
    printf("Error: %s in call to %s.%s, arguments: %s\n",
           $error, get_called_class(), $name, print_r($arguments, true));
    exit;
  }

}
