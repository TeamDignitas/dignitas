<?php

class RevisionHelpPage extends HelpPage {
  use RevisionTrait;

  /**
   * @param $prev The previous revision of the same help page.
   * @return ObjectDiff
   */
  function compare($prev) {
    $od = new ObjectDiff($this);

    $this->compareField(_('label-category'),
                        $prev->getCategory()->name,
                        $this->getCategory()->name,
                        $od, Ct::FIELD_CHANGE_STRING);

    return $od;
  }
}
