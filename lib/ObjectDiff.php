<?php

/**
 * This class stores differences between two consecutive revisions of an
 * object.
 */
class ObjectDiff {

  public $modUser;
  public $modDate;
  private $textChanges;
  private $fieldChanges;

  /**
   * Loads all the revisions of an object and returns an ObjectDiff for every
   * pair of consecutive revisions.
   */
  static function getRevisions($obj) {
    $results = [];

    $revisions = $obj->getHistory();
    for ($i = 0; $i < count($revisions) - 1; $i++) {
      $od = $revisions[$i]->compare($revisions[$i + 1]);
      if (!$od->isEmpty()) {
        $results[] = $od;
      }
    }

    return $results;
  }

  function __construct($revision) {
    $this->modUser = $revision->getModUser();
    $this->modDate = $revision->modDate;
    $this->textChanges = [];
    $this->fieldChanges = [];
  }

  function addTextChange($title, $ses) {
    $this->textChanges[] = [
      'title' => $title,
      'ses' => $ses,
    ];
  }

  function addFieldChange($type, $title, $old, $new) {
    $this->fieldChanges[] = [
      'type' => $type,
      'title' => $title,
      'old' => $old,
      'new' => $new,
    ];
  }

  function getTextChanges() {
    return $this->textChanges;
  }

  function getFieldChanges() {
    return $this->fieldChanges;
  }

  function isEmpty() {
    return empty($this->textChanges) && empty($this->fieldChanges);
  }

}
