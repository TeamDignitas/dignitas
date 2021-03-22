<?php

class ArchivedLink extends Precursor {

  const STATUS_NEW = 1;       // when we initially extract the link
  const STATUS_ARCHIVED = 2;  // once the archiver has it
  const STATUS_FAILED = 3;    // if not available after an attempted archival
  const STATUS_DELETED = 4;   // when scheduled for deletion

  static function create(Object $obj, String $url) {
    $al = Model::factory('ArchivedLink')->create();
    $al->status = self::STATUS_NEW;
    $al->objectType = $obj->getObjectType();
    $al->objectId = $obj->id;
    $al->url = $url;
    $al->createDate = time();
    $al->save();
    return $al;
  }

  static function getForObject($obj) {
    return self::get_all_by_objectType_objectId($obj->getObjectType(), $obj->id);
  }

  /**
   * Returns the URL for the Dignitas page wrapping the archived version of
   * this link, or false if the link is not archived.
   */
  function getViewUrl() {
    if ($this->status == self::STATUS_ARCHIVED &&
        $this->timestamp &&
        $this->path) {
      return Router::link('archivedLink/view') . '/' . $this->id;
    }
    return false;
  }

  /**
   * Returns the URL for the archived version of this link, or false if the
   * link is not archived.
   */
  function getArchivedUrl() {
    if ($this->status == self::STATUS_ARCHIVED &&
        $this->timestamp &&
        $this->path) {
      return sprintf('%s%s/%s', Config::ARCHIVE_URL, $this->timestamp, $this->path);
    }
    return false;
  }

  /**
   * Returns the archival timestamp in seconds. Note: this is the timestamp
   * when the page was crawled, not when the ArchivedLink record was created.
   */
  function getTimestamp() {
    // discard fractional values if any
    $parts = explode('.', $this->timestamp);
    return $parts[0] ?? 0;
  }

  function markForDeletion() {
    $this->status = self::STATUS_DELETED;
    $this->save();
  }

  /**
   * Ensures that this link won't be deleted. If needed, marks it as new. If
   * the link still exists in the archive, it will subsequently be marked as
   * STATUS_ARCHIVED without beind refetched.
   */
  function unmarkForDeletion() {
    if ($this->status == self::STATUS_DELETED) {
      $this->status = self::STATUS_NEW;
      $this->save();
    }
  }

}
