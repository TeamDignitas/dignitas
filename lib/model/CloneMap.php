<?php

class CloneMap extends BaseObject {

  /**
   * Creates and saves a CloneMap record keeping track of a clone operation.
   *
   * @param PendingEditTrait $root Top-level clone
   * @param BaseObject $orig Dependant being cloned
   * @param BaseObject $clone Newly created clone of $orig
   */
  static function create($root, $orig, $clone) {
    $cm = Model::factory('CloneMap')->create();
    $cm->rootClass = get_class($root);
    $cm->rootId = $root->id;
    $cm->objectClass = get_class($orig);
    $cm->oldId = $orig->id;
    $cm->newId = $clone->id;
    $cm->save();
    return $cm;
  }

  /**
   * Returns the clone's ID given the original dependant. If $root is not a
   * pending edit object or if there is no record of $orig being cloned,
   * returns $orig's ID.
   *
   * @param PendingEditTrait $root Top-level clone
   * @param BaseObject $orig Dependant being cloned
   */
  static function getNewId($root, $orig) {
    $result = $orig->id;

    if ($root && $root->status == Ct::STATUS_PENDING_EDIT) {
      $cm = self::get_by_rootClass_rootId_objectClass_oldId(
        get_class($root),
        $root->id,
        get_class($orig),
        $orig->id);
      if ($cm) {
        $result = $cm->newId;
      }
    }

    return $result;
  }

  /**
   * Returns the original dependant given the cloned dependant. If there is no
   * record of cloning the dependant, returns null.
   *
   * @param PendingEditTrait $root Top-level clone
   * @param BaseObject $clone Cloned dependant
   */
  static function getOriginal($root, $clone) {
    $cm = CloneMap::get_by_rootClass_rootId_objectClass_newId(
      get_class($root),
      $root->id,
      get_class($clone),
      $clone->id);

    if ($cm) {
      return Model::factory(get_class($clone))->where('id', $cm->oldId)->find_one();
    } else {
      return null;
    }
  }

  /**
   * Deletes all information regarding cloned dependants of $root.
   *
   * @param PendingEditTrait $root Top-level clone
   */
  static function deleteRoot($root) {
    self::delete_all_by_rootClass_rootId(get_class($root), $root->id);
  }
}
