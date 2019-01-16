<?php

class User extends BaseObject implements DatedObject {

  private static $active = null; // user currently logged in

  static function getActive() {
    return self::$active;
  }

  static function setActive($userId) {
    self::$active = User::get_by_id($userId);
  }

  public function __toString() {
    return $this->email;
  }

}
