<?php

class RevisionHelpPageT extends HelpPage {
  use RevisionTrait;

  /**
   * @param $prev The previous revision of the same help page.
   * @return ObjectDiff
   */
  function compare($prev) {
    $od = new ObjectDiff($this);

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

    // categoryId belongs to HelpPage, not HelpPageT. Therefore, look for a
    // revision_help_page with the same requestId
    $hpt = RevisionHelpPage::get_by_requestId($this->requestId);
    if ($hpt) { // paranoia
      $prevHpt = Model::factory('RevisionHelpPage')
        ->where('id', $this->pageId)
        ->where_lt('revisionId', $hpt->revisionId)
        ->order_by_desc('revisionId')
        ->find_one();
      $cat = HelpCategory::get_by_id($hpt->categoryId);
      $prevCat = HelpCategory::get_by_id($prevHpt->categoryId);
      $this->compareField(_('label-category'),
                          $prevCat->getName(),
                          $cat->getName(),
                          $od, Ct::FIELD_CHANGE_STRING);
    }

    return $od;
  }
}
