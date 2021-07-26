<?php

/**
 * Method implementations for objects that support drafts (statements and answers).
 */
trait DraftTrait {

  /**
   * Returns true iff the object is a saved draft.
   */
  function isDraft() {
    return ($this->status == Ct::STATUS_DRAFT) && $this->id;
  }

  /**
   * Returns true iff the object is a draft OR it is being created.
   */
  function isDraftOrNew() {
    return ($this->status == Ct::STATUS_DRAFT) || !$this->id;
  }

  /**
   * Rewrites the history of $this to delete all its draft revisions.
   * Call us when a draft (statement or answer) is publicized.
   */
  function deleteDraftRevisions() {
    $class = $this->getRevisionClass();

    Model::factory($class)
      ->where('id', $this->id)
      ->where('status', Ct::STATUS_DRAFT)
      ->delete_many();

    // there should be exactly one of these
    $rev = Model::factory($class)
      ->where('id', $this->id)
      ->find_one();
    $rev->revisionAction = 'insert';
    $rev->save();
  }

}
