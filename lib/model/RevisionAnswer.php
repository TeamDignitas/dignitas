<?php

class RevisionAnswer extends Answer {
  use RevisionTrait;

  /**
   * @param $prev The previous revision of the same answer.
   * @return ObjectDiff
   */
  function compare($prev) {
    $od = new ObjectDiff($this);

    $this->diffField(_('changes to contents'), $prev->contents, $this->contents, $od);

    $this->compareField(_('status'),
                        $prev->getStatusName(),
                        $this->getStatusName(),
                        $od, Ct::FIELD_CHANGE_STRING);

    return $od;
  }
}
