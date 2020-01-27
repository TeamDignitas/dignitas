<?php

/**
 * This class stores differences between two consecutive revisions of an
 * object.
 */
class ObjectDiff {

  public $modUser;
  public $modDate;
  private $textChanges;
  private $fieldChanges;
  public $review;

  /**
   * Loads all the revisions of an object and returns an ObjectDiff for every
   * pair of consecutive revisions.
   *
   * @return ObjectDiff[]
   */
  static function loadFor($obj) {
    $results = [];

    $revisions = $obj->getHistory();
    for ($i = 0; $i < count($revisions) - 1; $i++) {
      $od = $revisions[$i]->compare($revisions[$i + 1]);
      if (!$od->isEmpty()) {
        $results[] = $od;
      }
    }

    return $results;
  }

  function __construct($revision) {
    $this->modUser = $revision->getModUser();
    $this->modDate = $revision->modDate;
    $this->textChanges = [];
    $this->fieldChanges = [];
    $this->review = null;
  }

  function addTextChange($title, $ses) {
    $this->textChanges[] = [
      'title' => $title,
      'ses' => $ses,
    ];
  }

  function addFieldChange($type, $title, $old, $new) {
    $this->fieldChanges[] = [
      'type' => $type,
      'title' => $title,
      'old' => $old,
      'new' => $new,
    ];
  }

  /**
   * Adds the review that approved this pending edit, if applicable.
   *
   * @param PendingEditTrait $old revision that created the pending edit
   * @param PendingEditTrait $new revision that approved the pending edit
   */
  function checkReview($old, $new) {
    if ($old->pendingEditId) {
      // rev1 should exist, but rev2 may not exist if the pending edit was rejected
      $rev1 = RevisionReview::get_by_revisionAction_requestId_objectType_objectId_reason_status(
        'insert',
        $old->requestId,
        $old->getObjectType(),
        $old->id,
        Ct::REASON_PENDING_EDIT,
        Review::STATUS_PENDING);
      $rev2 = RevisionReview::get_by_revisionAction_requestId_id_objectType_objectId_reason_status(
        'update',
        $new->requestId,
        $rev1->id ?? null,
        $new->getObjectType(),
        $new->id,
        Ct::REASON_PENDING_EDIT,
        Review::STATUS_KEEP);
      if ($rev1 && $rev2) {
        $this->review = Review::get_by_id($rev2->id);
        Log::info('Adding review #%d for revisions #%d and #%d.',
                  $rev2->id, $new->revisionId, $old->revisionId);
      }
    }
  }

  function getTextChanges() {
    return $this->textChanges;
  }

  function getFieldChanges() {
    return $this->fieldChanges;
  }

  function isEmpty() {
    return empty($this->textChanges) && empty($this->fieldChanges);
  }

}
