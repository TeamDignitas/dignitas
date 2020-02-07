<?php

class Variable extends Proto {

  static function peek($name, $default = null) {
    $v = Variable::get_by_name($name);
    return $v ? $v->value : $default;
  }

  static function poke($name, $value) {
    $v = Variable::get_by_name($name);
    if (!$v) {
      $v = Model::factory('Variable')->create();
      $v->name = $name;
    }
    $v->value = $value;
    $v->save();
  }

}
