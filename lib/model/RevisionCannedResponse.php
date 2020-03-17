<?php

class RevisionCannedResponse extends CannedResponse {
  use RevisionTrait;

  /**
   * @param $prev The previous revision of the same canned response.
   * @return ObjectDiff
   */
  function compare($prev) {
    $od = new ObjectDiff($this);

    // object fields
    $this->diffField(_('title-changes-contents'), $prev->contents, $this->contents, $od);

    return $od;
  }
}
