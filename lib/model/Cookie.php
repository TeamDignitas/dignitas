<?php

class Cookie extends BaseObject {

  static function create($userId) {
    $c = Model::factory('Cookie')->create();
    $c->userId = $userId;
    $c->string = Str::randomString(40);
    $c->save();
    return $c;
  }

}
