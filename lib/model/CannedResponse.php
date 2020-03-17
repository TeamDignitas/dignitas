<?php

class CannedResponse extends Proto {

  static function loadAll() {
    return Model::factory('CannedResponse')
      ->order_by_asc('rank')
      ->find_many();
  }

}
