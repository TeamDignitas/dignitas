<?php

class Region extends Proto {

  // populated during loadTree()
  public $children = [];

  function getObjectType() {
    return Proto::TYPE_REGION;
  }

  // Returns an array of root regions with their $children fields populated
  static function loadTree() {
    $regions = Model::factory('Region')->order_by_asc('name')->find_many();

    // Map the regions by id
    $map = [];
    foreach ($regions as $r) {
      $map[$r->id] = $r;
    }

    // Make each region its parent's child
    foreach ($regions as $r) {
      if ($r->parentId) {
        $p = $map[$r->parentId];
        $p->children[] = $r;
      }
    }

    // Return just the roots
    return array_filter($regions, function($r) {
      return !$r->parentId;
    });
  }

  function getAncestors() {
    $p = $this;
    $result = [];

    do {
      array_unshift($result, $p);
      $p = Region::get_by_id($p->parentId);
    } while ($p);

    return $result;
  }

  /**
   * If the given depth differs from the curent depth, changes the depth and
   * call all children recursively. Otherwise does nothing.
   */
  function recursiveDepthUpdate($depth) {
    if ($this->depth == $depth) {
      return;
    }

    Log::info("Depth of region [#{$this->id}]({$this->name}) changed from {$this->depth} to {$depth}");
    $this->depth = $depth;
    $this->save();

    $children = Region::get_all_by_parentId($this->id);
    foreach ($children as $c) {
      $c->recursiveDepthUpdate($depth + 1);
    }
  }

  /**
   * @return bool True iff the current user may delete this region.
   */
  function isDeletable() {
    return
      $this->id &&
      !Ban::exists(Ban::TYPE_TAG) &&
      User::may(User::PRIV_DELETE_TAG);
  }

  function delete() {
    Log::warning("Deleted region {$this->id} ({$this->name})");
    parent::delete();
  }

}
