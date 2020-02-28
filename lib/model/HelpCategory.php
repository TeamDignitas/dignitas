<?php

class HelpCategory extends Proto {

  static function loadAll() {
    return Model::factory('HelpCategory')
      ->order_by_asc('rank')
      ->find_many();
  }

  function getPages() {
    return Model::factory('HelpPage')
      ->where('categoryId', $this->id)
      ->order_by_asc('rank')
      ->find_many();
  }

}
