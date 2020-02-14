<?php

class Domain extends Proto {
  use UploadTrait;
  
  function getObjectType() {
    return Proto::TYPE_DOMAIN;
  }

  private function getFileSubdirectory() {
    return 'domain';
  }

  private function getFileRoute() {
    return 'domain/image';
  }

  static function loadAll() {
    return Model::factory('Domain')
      ->order_by_asc('name')
      ->find_many();
  }

  function delete() {
    $links = Link::get_all_by_domainId($this->id);
    foreach ($links as $l) {
      $l->domainId = 0;
      $l->save();
    }
    parent::delete();
  }

}
