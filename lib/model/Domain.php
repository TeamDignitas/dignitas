<?php

class Domain extends Proto {
  use UploadTrait;

  function getObjectType() {
    return Proto::TYPE_DOMAIN;
  }

  function getEditUrl() {
    return Router::link('domain/edit') . '/' . $this->id;
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

  /**
   * Associates all currently unassociated links that match this domain.
   */
  function associateLinks() {
    $links = Model::factory('Link')
      ->where('domainId', 0)
      ->where_raw("url rlike '^(http|https)://{$this->name}/'")
      ->find_many();
    Log::debug(count($links));
    foreach ($links as $l) {
      $l->domainId = $this->id;
      $l->save();
    }
    return count($links);
  }

  /**
   * Dissociates all links from this domain. Called when changing the domain
   * name.
   */
  function dissociateLinks() {
    $links = Link::get_all_by_domainId($this->id);
    foreach ($links as $l) {
      $l->domainId = 0;
      $l->save();
    }
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
