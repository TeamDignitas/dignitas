<?php

class RevisionAnswer extends Answer {
  use RevisionTrait;

  /**
   * @param $prev The previous revision of the same answer.
   * @return ObjectDiff
   */
  function compare($prev) {
    $od = new ObjectDiff($this);

    $this->diffField(_('title-changes-contents'), $prev->contents, $this->contents, $od);

    $this->compareField(_('label-verdict'),
                        $prev->getVerdictName(),
                        $this->getVerdictName(),
                        $od, Ct::FIELD_CHANGE_STRING);
    $this->compareField(_('label-status'),
                        $prev->getStatusName(),
                        $this->getStatusName(),
                        $od, Ct::FIELD_CHANGE_STRING);

    $od->checkReview($this);

    return $od;
  }

  /**
   * Rewrites the history of $answer to delete all its draft revisions.
   * Call us when an answer is publicized.
   */
  static function deleteDraftRevisions($answer) {
    Model::factory('RevisionAnswer')
      ->where('id', $answer->id)
      ->where('status', Ct::STATUS_DRAFT)
      ->delete_many();

    // there should be exactly one of these
    $rev = Model::factory('RevisionAnswer')
      ->where('id', $answer->id)
      ->find_one();
    $rev->revisionAction = 'insert';
    $rev->save();
  }

}
