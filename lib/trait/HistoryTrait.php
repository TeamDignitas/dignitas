<?php

/**
 * Method implementations for objects that have a displayable history.
 */
trait HistoryTrait {

  /**
   * Returns an array of the object's fields that should be diffed as text
   * (either single or multiple line). Each field should be described as a
   * pair of [ field name, descriptive title ] strings.
   *
   * @return array
   */
  abstract function getTextDiffFields();

  /**
   * Returns an array of the object's changed fields that should be compared
   * and reported as such, without diffing. Each field should be described as
   * a tuple of [ title, old value, new value ] strings.
   *
   * @param HistoryTrait $old The previos version of $this
   * @return array
   */
  abstract function getFieldChanges($old);


  /**
   * Returns a pair of arrays: the object's added and deleted tags.
   * TODO there is no guarantee that the object has a getTags() method
   *
   * @return array
   */
  function getTagChanges($old) {
    $added = [];
    $deleted = [];

    foreach ($this->getTags() as $tag) {
      $added[$tag->id] = $tag;
    }

    foreach ($old->getTags() as $tag) {
      if (isset($added[$tag->id])) {
        unset($added[$tag->id]);
      } else {
        $deleted[$tag->id] = $tag;
      }
    }

    return [
      'added' => $added,
      'deleted' => $deleted,
    ];
  }

  /**
   * Returns an array of changes, one for each pair of consecutive
   * versions. Each change includes
   * - The author who made the change.
   * - The timestamp of the change.
   * - Text diffs for fields listed by getTextDiffFields(). Both single and
   *   multiple line diffs are supported. Each record contains:
   *   - a localized description;
   *   - the SES produced by Diff.php.
   * - Field changes for fields listed by getComparableFields(). These will
   *   not go through the diff algorithm, but will simply be presented as old
   *   value > new value. Each record contains:
   *   - a localized description;
   *   - the old value;
   *   - the new value;
   */
  function getDisplayHistory() {
    $results = [];

    $versions = $this->getHistory();
    for ($i = 0; $i < count($versions) - 1; $i++) {
      $new = $versions[$i];
      $old = $versions[$i + 1];
      $rec = [];

      $rec['modUser'] = $new->getModUser();
      $rec['modDate'] = $new->modDate;

      $rec['textDiffs'] = [];
      $fields = $this->getTextDiffFields();
      foreach ($fields as $f) {
        $field = $f[0];
        $title = $f[1];
        if ($old->$field != $new->$field) {
          $rec['textDiffs'][] = [
            'title' => $title,
            'ses' => Diff::sesText($old->$field, $new->$field),
          ];
        }
      }

      $rec['fieldChanges'] = $new->getFieldChanges($old);
      $rec['tagChanges'] = $new->getTagChanges($old);

      $results[] = $rec;
    }

    return $results;
  }

}
