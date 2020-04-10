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
    $changes = [];

    $revisions = $obj->getHistory();
    for ($i = 0; $i < count($revisions) - 1; $i++) {
      $od = $revisions[$i]->compare($revisions[$i + 1]);
      if (!$od->isEmpty()) {
        $changes[] = $od;
      }
    }

    // also load rejected suggested changes and reopen reviews
    $pendingEditIds = Model::factory('RevisionStatement')
      ->table_alias('rs')
      ->select('rs.id')
      ->join('revision_review', [ 'rs.requestId', '=', 'rr.requestId' ], 'rr')
      ->where('rr.objectType', $obj->getObjectType())
      ->where('rr.objectId', $obj->id)
      ->where_in('rr.reason', [ Ct::REASON_PENDING_EDIT, Ct::REASON_REOPEN ])
      ->where_in('rr.status', [ Review::STATUS_REMOVE, Review::STATUS_STALE])
      ->where('rs.revisionAction', 'delete')
      ->order_by_desc('rs.revisionId')
      ->find_many();
    $pendingEditIds = Util::objectProperty($pendingEditIds, 'id');

    $rejected = [];
    foreach ($pendingEditIds as $id) {
      $before = RevisionStatement::get_by_id_revisionAction($id, 'insert');
      $after = RevisionStatement::get_by_id_revisionAction($id, 'update');
      $od = $after->compare($before);
      if (!$od->isEmpty()) {
        $rejected[] = $od;
      }
    }

    // now merge $changes and $rejected by decreasing modDate
    $results = [];
    $i = $j = 0;
    while (($i < count($changes)) || ($j < count($rejected))) {
      if (($j >= count($rejected)) ||
          (($i < count($changes)) && ($changes[$i]->modDate > $rejected[$j]->modDate))) {
        $results[] = $changes[$i++];
      } else {
        $results[] = $rejected[$j++];
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
   *   - rejected pending edits;
   *   - flagging for closure or deletion (which changes the status)
   *
   * TODO: this can bug out if the object is flagged while edits are pending.
   * This inserts additional revisions.
   *
   * @param PendingEditTrait $new An object revision.
   */
  function checkReview($new) {
    $rev = RevisionReview::get_by_requestId($new->requestId);
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

  function isRejectedEdit() {
    return ($this->review &&
            $this->review->reason == Ct::REASON_PENDING_EDIT &&
            $this->review->status != Review::STATUS_KEEP);
  }

}
