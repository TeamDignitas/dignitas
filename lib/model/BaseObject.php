<?php

class BaseObject extends Model {
  const ACTION_SELECT = 1;
  const ACTION_SELECT_ALL = 2;
  const ACTION_DELETE_ALL = 3;

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
      case self::ACTION_DELETE_ALL:
        $objects = $clause->find_many();
        foreach ($objects as $o) {
          $o->delete();
        }
        break;
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

  static function getField($colname, $id) {
    $result = Model::factory(get_called_class())
        ->select_many($colname)
        ->find_one($id);
    return $result->$colname;
  }

  static function _die($error, $name, $arguments) {
    printf("Error: %s in call to %s.%s, arguments: %s\n",
           $error, get_called_class(), $name, print_r($arguments, true));
    exit;
  }

}
