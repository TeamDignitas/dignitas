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

  /**
   * What type of object are we? Children may override this.
   *
   * @return int One of the BaseObject::TYPE_* values.
   */
  function getObjectType() {
    return self::TYPE_UNKNOWN;
  }

  // Updates a list of dependants, e.g. a list of Relations for an
  // Entity. Deletes DB records not present in this list, inserts new DB
  // records where needed, and updates the rank field.
  static function updateDependants($objects, $fkField, $fkValue, $rankField) {
    $class = get_called_class();

    // delete vanishing DB records
    $existingIds = array_filter(Util::objectProperty($objects, 'id'));
    $existingIds[] = 0; // ensure array is non-empty
    Model::factory($class)
      ->where($fkField, $fkValue)
      ->where_not_in('id', $existingIds)
      ->delete_many();

    // update or insert existing objects
    $rank = 0;
    foreach ($objects as $o) {
      $o->$fkField = $fkValue;
      $o->$rankField = ++$rank;
      $o->save();
    }
  }

  function save() {
    /* auto-save the createDate and modDate fields if the model has them */
    if ($this instanceof DatedObject) {
      $this->modDate = time();
      if (!$this->createDate) {
        $this->createDate = $this->modDate;
      }
    }
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

  static function _die($error, $name, $arguments) {
    printf("Error: %s in call to %s.%s, arguments: %s\n",
           $error, get_called_class(), $name, print_r($arguments, true));
    exit;
  }

}
