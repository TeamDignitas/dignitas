<?php

class RevisionHelpPageT extends HelpPage {
  use RevisionTrait;

  /**
   * @param $prev The previous revision of the same help page.
   * @return ObjectDiff
   */
  function compare($prev) {
    $od = new ObjectDiff($this);

    // object fields
    $this->diffField(
      _('title-changes-contents'),
      $prev->contents,
      $this->contents,
      $od);

    $this->compareField(_('label-title'),
                        $prev->title,
                        $this->title,
                        $od, Ct::FIELD_CHANGE_STRING);
    $this->compareField(_('label-help-page-path'),
                        $prev->path,
                        $this->path,
                        $od, Ct::FIELD_CHANGE_STRING);

    return $od;
  }
}
