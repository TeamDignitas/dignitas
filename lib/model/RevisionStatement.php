<?php

class RevisionStatement extends Statement {
  use RevisionTrait;

  /**
   * @param $prev The previous revision of the same statement.
   * @return ObjectDiff
   */
  function compare($prev) {
    $od = new ObjectDiff($this);

    // object fields
    $this->diffField(_('title-changes-summary'), $prev->summary, $this->summary, $od);
    $this->diffField(_('title-changes-context'), $prev->context, $this->context, $od);
    $this->diffField(_('title-changes-goal'), $prev->goal, $this->goal, $od);

    $this->compareField(_('label-statement-author'),
                        (string)$prev->getEntity(),
                        (string)$this->getEntity(),
                        $od, Ct::FIELD_CHANGE_STRING);
    $this->compareField(_('label-statement-date'),
                        Time::localDate($prev->dateMade),
                        Time::localDate($this->dateMade),
                        $od, Ct::FIELD_CHANGE_STRING);
    $this->compareField(_('label-status'),
                        $prev->getStatusName(),
                        $this->getStatusName(),
                        $od, Ct::FIELD_CHANGE_STRING);

    // added / removed tags
    $tags = RevisionObjectTag::getChangesFor($this, 'insert');
    $this->compareField(_('label-added-tags'),
                        $tags,
                        [],
                        $od, Ct::FIELD_CHANGE_TAG_LIST);

    $tags = RevisionObjectTag::getChangesFor($this, 'delete');
    $this->compareField(_('label-deleted-tags'),
                        $tags,
                        [],
                        $od, Ct::FIELD_CHANGE_TAG_LIST);

    // added / removed / edited links
    $links = RevisionLink::getChangesFor($this, 'insert');
    $this->compareField(_('label-added-statement-links'),
                        $links,
                        [],
                        $od, Ct::FIELD_CHANGE_LINK_LIST);

    $links = RevisionLink::getChangesFor($this, 'delete');
    $this->compareField(_('label-deleted-statement-links'),
                        $links,
                        [],
                        $od, Ct::FIELD_CHANGE_LINK_LIST);

    $links = RevisionLink::getChangesFor($this, 'update');
    foreach ($links as $l) {
      $prevL = $l->getPreviousRevision();
      $this->compareField(_('label-changed-statement-link'),
                          (string)$prevL,
                          (string)$l,
                          $od, Ct::FIELD_CHANGE_LINK);
    }
    return $od;
  }
}
