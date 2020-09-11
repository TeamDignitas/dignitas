<?php

class CannedResponse extends Proto {

  function getHistoryUrl() {
    return Router::link('cannedResponse/history') . '/' . $this->id;
  }

  static function loadAll() {
    return Model::factory('CannedResponse')
      ->order_by_asc('rank')
      ->find_many();
  }

}
