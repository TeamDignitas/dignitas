<?php

class Tag extends BaseObject implements DatedObject {

  const DEFAULT_COLOR = '#ffffff';
  const DEFAULT_BACKGROUND = '#1e83c2';

  // populated during loadTree()
  public $children = [];

  function getColor() {
    return $this->color ? $this->color : self::DEFAULT_COLOR;
  }

  function setColor($color) {
    $this->color = ($color == self::DEFAULT_COLOR) ? '' : $color;
  }

  function getBackground() {
    return $this->background ? $this->background : self::DEFAULT_BACKGROUND;
  }

  function setBackground($background) {
    $this->background = ($background == self::DEFAULT_BACKGROUND) ? '' : $background;
  }

  static function getFrequentValues($field, $default) {
    $data = Model::factory('Tag')
      ->select($field)
      ->group_by($field)
      ->order_by_expr('count(*) desc')
      ->limit(10)
      ->find_many();

    $results = [];
    foreach ($data as $row) {
      $results[] = $row->$field ? $row->$field : $default;
    }
    return $results;
  }

  static function loadByObject($objectType, $objectId) {
    return Model::factory('Tag')
      ->select('Tag.*')
      ->join('ObjectTag', ['Tag.id', '=', 'tagId'])
      ->where('ObjectTag.objectType', $objectType)
      ->where('ObjectTag.objectId', $objectId)
      ->order_by_asc('ObjectTag.id')
      ->find_many();
  }

  // Returns an array of root tags with their $children fields populated
  static function loadTree() {
    $tags = Model::factory('Tag')->order_by_asc('value')->find_many();

    // Map the tags by id
    $map = [];
    foreach ($tags as $t) {
      $map[$t->id] = $t;
    }

    // Make each tag its parent's child
    foreach ($tags as $t) {
      if ($t->parentId) {
        $p = $map[$t->parentId];
        $p->children[] = $t;
      }
    }

    // Return just the roots
    return array_filter($tags, function($t) {
      return !$t->parentId;
    });
  }

  function getAncestors() {
    $p = $this;
    $result = [];

    do {
      array_unshift($result, $p);
      $p = Tag::get_by_id($p->parentId);
    } while ($p);

    return $result;
  }

  // returns the IDs of all ancestors of $tagId, including $tagId
  static function getAncestorIds($tagId) {
    $tag = Tag::get_by_id($tagId);
    $ancestors = $tag->getAncestors();
    $ids = Util::objectProperty($ancestors, 'id');
    return $ids;
  }

  function delete() {
    Log::warning("Deleted tag {$this->id} ({$this->value})");
    ObjectTag::delete_all_by_tagId($this->id);
    parent::delete();
  }

}
