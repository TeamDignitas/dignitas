<?php

class Tag extends Proto {

  const DEFAULT_COLOR = '#ffffff';
  const DEFAULT_BACKGROUND = '#1e83c2';

  // populated during loadSubtree()
  public $children = [];

  function getObjectType() {
    return Proto::TYPE_TAG;
  }

  function getEditUrl() {
    return Router::link('tag/edit') . '/' . $this->id;
  }

  function getColor() {
    return $this->color ? $this->color : self::DEFAULT_COLOR;
  }

  function setColor($color) {
    $this->color = strcasecmp($color, self::DEFAULT_COLOR) ? $color : '';
  }

  function getBackground() {
    return $this->background ? $this->background : self::DEFAULT_BACKGROUND;
  }

  function setBackground($background) {
    $this->background = strcasecmp($background, self::DEFAULT_BACKGROUND) ? $background : '';
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

  /**
   * Loads the subtree of this tag. Populates the $children field for the tag
   * and its subtree.
   */
  function loadSubtree() {
    $map = [ (int)$this->id => $this ];
    $list = [ (int)$this->id ];

    // at the k-th iteration, we load all the descendants k levels below $this
    while (!empty($list)) {
      $childTags = Model::factory('Tag')
        ->where_in('parentId', $list)
        ->order_by_asc('value')
        ->find_many();

      $list = []; // start preparing the list for the next level

      foreach ($childTags as $ct) {
        $map[$ct->id] = $ct;
        $parent = $map[$ct->parentId];
        $parent->children[] = $ct;
        $list[] = $ct->id;
      }
    }
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

  /**
   * Returns a query that loads or counts statements tagged with $this and
   * visible to the current user.
   *
   * @return ORMWrapper
   */
  function getStatementQuery() {
    $query = Model::factory('Statement')
      ->select('s.*')
      ->table_alias('s')
      ->join('object_tag', ['ot.objectId', '=', 's.id'], 'ot')
      ->where('ot.objectType', ObjectTag::TYPE_STATEMENT)
      ->where('ot.tagId', $this->id);

    return Statement::filterViewable($query);
  }

  /**
   * @return bool True iff the current user may delete this tag.
   */
  function isDeletable() {
    return
      $this->id &&
      !Ban::exists(Ban::TYPE_TAG) &&
      User::may(User::PRIV_DELETE_TAG);
  }

  function delete() {
    Log::warning("Deleted tag {$this->id} ({$this->value})");
    ObjectTag::delete_all_by_tagId($this->id);
    parent::delete();
  }

}
