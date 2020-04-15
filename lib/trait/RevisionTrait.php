<?php

/**
 * Method implementations for revision objects.
 */
trait RevisionTrait {

  /**
   * Returns the revision immediately before this revision.
   *
   * @return RevisionTrait An object of the same class as $this.
   */
  function getPreviousRevision() {
    return Model::factory(get_class($this))
      ->where('id', $this->id)
      ->where_lt('revisionId', $this->revisionId)
      ->order_by_desc('revisionId')
      ->find_one();
  }

  /**
   * Checks if a field has changed between two revisions. If so, then adds a
   * SES (shortest edit script) to the specified ObjectDiff.
   *
   * @param string $title Descriptive title to use when we print the diff
   * @param string $old Previous value
   * @param string $new Current value
   * @param ObjectDiff $od ObjectDiff where we build the diff between revisions.
   */
  function diffField($title, $old, $new, &$od) {
    if ($old != $new) {
      $od->addTextChange(
        $title,
        Diff::sesText($old, $new));
    }
  }

  /**
   * Checks if a field or dependant has changed between two revisions. If so,
   * then adds a record to the ObjectDiff, specifying the field type. This
   * helps the frontend highlight the differences correctly.
   *
   * @param string $title Descriptive title to use when we print the diff
   * @param string $old Previous value
   * @param string $new Current value
   * @param ObjectDiff $od ObjectDiff where we build the diff between revisions.
   * @param int $type One of the Ct::FIELD_CHANGE_* values.
   */
  function compareField($title, $old, $new, &$od, $type) {
    if ($old != $new) {
      $od->addFieldChange($type, $title, $old, $new);
    }
  }

  function addDuplicateInfo($oldDuplicateId, $newDuplicateId, &$od, $duplicate) {
    if ($newDuplicateId && ($oldDuplicateId != $newDuplicateId)) {
      $od->addDuplicate($duplicate);
    }
  }

}
