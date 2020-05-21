<?php

class StaticResource extends Proto {

  // <shared_drive>/staticResource/<locale>/<file>
  const FILE_PATTERN = '%sstaticResource/%s/%s';
  const ALL_LOCALE = 'all';

  private $contents = null;

  function getObjectType() {
    return Proto::TYPE_STATIC_RESOURCE;
  }

  static function loadAll() {
    return Model::factory('StaticResource')
      ->order_by_asc('name')
      ->order_by_asc('locale')
      ->find_many();
  }

  /**
   * In this order, returns
   * - contents previously set with setContents() (while editing);
   * - underlying file contents if the file is text (HTML, CSS, Javascript);
   * - empty string if the file is binary (e.g. images).
   */
  function getEditableContents() {
    if ($this->contents !== null) {
      return $this->contents;
    } else {
      return @file_get_contents($this->getFilePath());
    }
  }

  function setContents($contents) {
    $this->contents = $contents;
  }

  function getFilePath() {
    $locale = $this->locale ?: self::ALL_LOCALE;
    return $this->id
      ? sprintf(self::FILE_PATTERN, Config::SHARED_DRIVE, $locale, $this->name)
      : '';
  }

  // Saves a static resource that may contain a new uploaded file. If the name
  // or locale change, orphan files will be left behind. A cleanup script will
  // delete them.
  function saveWithFile($fileData) {
    $this->save();

    // Take contents from the file if supplied, otherwise from the contents
    // field.
    if ($fileData['status'] == Request::UPLOAD_OK) {
      $contents = @file_get_contents($fileData['tmpFileName']);
    } else {
      $contents = $this->contents;
    }

    $dest = $this->getFilePath();
    @mkdir(dirname($dest), 0777, true);
    file_put_contents($dest, $contents);
  }

  function delete() {
    @unlink($this->getFilePath());
    parent::delete();
  }

}
