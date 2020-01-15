<?php

class HistoryStatement extends Statement {
  use HistoryTrait;

  /**
   * @param $prev The previous revision of the same statement.
   * @return ObjectDiff
   */
  function compare($prev) {
    $od = new ObjectDiff($this);

    // object fields
    $this->diffField(_('changes to summary'), $prev->summary, $this->summary, $od);
    $this->diffField(_('changes to context'), $prev->context, $this->context, $od);
    $this->diffField(_('changes to goal'), $prev->goal, $this->goal, $od);

    $this->compareField(_('author'),
                        (string)$prev->getEntity(),
                        (string)$this->getEntity(),
                        $od, Ct::FIELD_CHANGE_STRING);
    $this->compareField(_('statement date'),
                        Time::localDate($prev->dateMade),
                        Time::localDate($this->dateMade),
                        $od, Ct::FIELD_CHANGE_STRING);
    $this->compareField(_('status'),
                        $prev->getStatusName(),
                        $this->getStatusName(),
                        $od, Ct::FIELD_CHANGE_STRING);

    // added / removed tags
    $tags = HistoryObjectTag::getChangesFor($this, 'insert');
    $this->compareField(_('added tags'),
                        $tags,
                        [],
                        $od, Ct::FIELD_CHANGE_TAG_LIST);

    $tags = HistoryObjectTag::getChangesFor($this, 'delete');
    $this->compareField(_('deleted tags'),
                        $tags,
                        [],
                        $od, Ct::FIELD_CHANGE_TAG_LIST);

    // added / removed / edited statement sources
    $ss = HistoryStatementSource::getChangesFor($this, 'insert');
    $this->compareField(_('added sources'),
                        $ss,
                        [],
                        $od, Ct::FIELD_CHANGE_URL_LIST);

    $ss = HistoryStatementSource::getChangesFor($this, 'delete');
    $this->compareField(_('deleted sources'),
                        $ss,
                        [],
                        $od, Ct::FIELD_CHANGE_URL_LIST);

    $ss = HistoryStatementSource::getChangesFor($this, 'update');
    foreach ($ss as $source) {
      $prevSource = $source->getPreviousRevision();
      $this->compareField(_('changed source'),
                          (string)$prevSource,
                          (string)$source,
                          $od, Ct::FIELD_CHANGE_URL);
    }
    return $od;
  }
}
