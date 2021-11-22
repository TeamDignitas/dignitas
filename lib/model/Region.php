<?php

class Region extends Proto {

  // populated during loadTree()
  public $children = [];

  function getObjectType() {
    return Proto::TYPE_REGION;
  }

  function getViewUrl() {
    // For SEO purposes we also output a URL-friendly name.
    return sprintf('%s/%d/%s',
                   Router::link('region/view'),
                   $this->id,
                   Str::urlize($this->name));
  }

  /**
   * Returns the maximum depth among all regions or null if no regions are
   * defined.
   */
  static function getMaxDepth() {
    $r = Model::factory('Region')
      ->order_by_desc('depth')
      ->find_one();
    return $r ? $r->depth : null;
  }

  /**
   * Region nomenclature (e.g. 'state' and 'county' for the US) is defined by
   * admins and stored in the variable table. This function returns the
   * variable name.
   */
  static function getVariableName($depth, $locale) {
    return "Region.{$depth}.{$locale}";
  }

  /**
   * Gets the region's nomenclature in the current locale
   */
  function getNomenclature() {
    $varName = self::getVariableName($this->depth, LocaleUtil::getCurrent());
    return Variable::peek($varName);
  }

  static function loadAll() {
    return Model::factory('Region')
      ->order_by_asc('name')
      ->find_many();
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
      User::isModerator();
  }

  function delete() {
    Log::warning("Deleted region {$this->id} ({$this->name})");
    parent::delete();
  }

}
