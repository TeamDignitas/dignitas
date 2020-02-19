<?php

class PasswordToken extends Proto {

  static function create($userId) {
    $pt = Model::factory('PasswordToken')->create();
    $pt->userId = $userId;
    $pt->token = Str::randomString(30);
    $pt->save();
    return $pt;
  }

}
