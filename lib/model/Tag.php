<?php

class Tag extends Proto {

  // keep these in sync with main-{light,dark}.scss
  const NUM_COLORS = 14;
  const DEFAULT_COLOR = 5;

  // populated during loadSubtree()
  public $children = [];

  function getObjectType() {
    return Proto::TYPE_TAG;
  }

  function getViewUrl() {
    return sprintf('%s/%d/%s',
                   Router::link('tag/view'),
                   $this->id,
                   Str::urlize($this->value));
  }

  function getEditUrl() {
    return Router::link('tag/edit') . '/' . $this->id;
  }

  function getCssStyle() {
    return sprintf(
      'style="background-color: var(--c-tag-bg-%d); color: var(--c-tag-%d);"',
      $this->color, $this->color);
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
