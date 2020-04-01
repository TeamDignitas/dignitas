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
   * Adds the review that caused this change, if any. This should cover
   *   - pending edits (which can change many fields);
   *   - flagging for closure or deletion (which changes the status)
   *
   * TODO: this can bug out if the object is flagged while edits are pending.
   * This inserts additional revisions.
   *
   * @param PendingEditTrait $new An object revision.
   */
  function checkReview($new) {
    $rev = Model::factory('RevisionReview')
      ->where('revisionAction', 'update')
      ->where('requestId', $new->requestId)
      ->where('objectType', $new->getObjectType())
      ->where('objectId', $new->id)
      ->where_in('status', [ Review::STATUS_KEEP, Review::STATUS_REMOVE])
      ->find_one();
    if ($rev) {
      $this->review = Review::get_by_id($rev->id);
      Log::info('Adding review #%d for revision #%d.', $rev->id, $new->revisionId);
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
