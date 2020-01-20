<?php

class RevisionEntity extends Entity {
  use RevisionTrait;

  /**
   * @param $prev The previous revision of the same statement.
   * @return ObjectDiff
   */
  function compare($prev) {
    $od = new ObjectDiff($this);

    // object fields
    $this->diffField(_('changes to profile'), $prev->profile, $this->profile, $od);

    $this->compareField(_('name'),
                        $prev->name,
                        $this->name,
                        $od, Ct::FIELD_CHANGE_STRING);
    $this->compareField(_('type'),
                        $prev->getTypeName(),
                        $this->getTypeName(),
                        $od, Ct::FIELD_CHANGE_STRING);
    $this->compareField(_('color'),
                        $prev->getColor(),
                        $this->getColor(),
                        $od, Ct::FIELD_CHANGE_COLOR);
    $this->compareField(_('status'),
                        $prev->getStatusName(),
                        $this->getStatusName(),
                        $od, Ct::FIELD_CHANGE_STRING);
    $this->compareField(_('image file'),
                        $prev->fileExtension,
                        $this->fileExtension,
                        $od, Ct::FIELD_CHANGE_STRING);

    // added / removed tags
    $tags = RevisionObjectTag::getChangesFor($this, 'insert');
    $this->compareField(_('added tags'),
                        $tags,
                        [],
                        $od, Ct::FIELD_CHANGE_TAG_LIST);

    $tags = RevisionObjectTag::getChangesFor($this, 'delete');
    $this->compareField(_('deleted tags'),
                        $tags,
                        [],
                        $od, Ct::FIELD_CHANGE_TAG_LIST);

    // added / removed / edited aliases
    $aliases = RevisionAlias::getChangesFor($this, 'insert');
    $this->compareField(_('added aliases'),
                        $aliases,
                        [],
                        $od, Ct::FIELD_CHANGE_STRING_LIST);

    $aliases = RevisionAlias::getChangesFor($this, 'delete');
    $this->compareField(_('deleted aliases'),
                        $aliases,
                        [],
                        $od, Ct::FIELD_CHANGE_STRING_LIST);

    $aliases = RevisionAlias::getChangesFor($this, 'update');
    foreach ($aliases as $a) {
      $prevAlias = $a->getPreviousRevision();
      $this->compareField(_('changed alias'),
                          (string)$prevAlias,
                          (string)$a,
                          $od, Ct::FIELD_CHANGE_STRING);
    }

    // added / removed / edited entity links
    $els = RevisionEntityLink::getChangesFor($this, 'insert');
    $this->compareField(_('added links'),
                        $els,
                        [],
                        $od, Ct::FIELD_CHANGE_URL_LIST);

    $els = RevisionEntityLink::getChangesFor($this, 'delete');
    $this->compareField(_('deleted links'),
                        $els,
                        [],
                        $od, Ct::FIELD_CHANGE_URL_LIST);

    $els = RevisionEntityLink::getChangesFor($this, 'update');
    foreach ($els as $el) {
      $prevEl = $el->getPreviousRevision();
      $this->compareField(_('changed link'),
                          (string)$prevEl,
                          (string)$el,
                          $od, Ct::FIELD_CHANGE_URL);
    }

    // added / removed / edited relations
    $relations = RevisionRelation::getChangesFor($this, 'insert');
    $this->compareField(_('added relations'),
                        $relations,
                        [],
                        $od, Ct::FIELD_CHANGE_RELATION_LIST);

    $relations = RevisionRelation::getChangesFor($this, 'delete');
    $this->compareField(_('deleted relations'),
                        $relations,
                        [],
                        $od, Ct::FIELD_CHANGE_RELATION_LIST);

    $relations = RevisionRelation::getChangesFor($this, 'update');
    foreach ($relations as $r) {
      $prevR = $r->getPreviousRevision();
      $this->compareField(_('changed relation'),
                          (string)$prevR,
                          (string)$r,
                          $od, Ct::FIELD_CHANGE_STRING);
    }

    return $od;
  }
}
