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
   * Finds a static resource by its name. If several choices exist, the order
   * of preference is:
   * - a resource in the current user's locale;
   * - a resource in the 'all' locale
   * - any available resource, breaking ties alphabetically by locale
   */
  static function getLocalizedByName($name) {
    $locale = LocaleUtil::getCurrent();
    $sr = self::get_by_name_locale($name, $locale);
    if (!$sr) {
      $sr = self::get_by_name_locale($name, '');
    }
    if (!$sr) {
      $sr = Model::factory('StaticResource')
        ->where('name', $name)
        ->order_by_asc('locale')
        ->find_one();
    }
    return $sr;
  }

  function getContents() {
    return @file_get_contents($this->getFilePath());
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
    } else if (!$this->id) {
      return ''; // object is being added
    } else {
      $path = $this->getFilePath();
      $mime = @mime_content_type($path);
      if (Str::startsWith($mime, 'text/')) {
        return file_get_contents($this->getFilePath());
      } else {
        return '';
      }
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

  function getUrl() {
    $locale = $this->locale ?: self::ALL_LOCALE;
    return sprintf('%s/%s/%s', Router::link('staticResource/view', true),
                   $locale, $this->name);
  }

  function render() {
    $path = $this->getFilePath();
    header('Content-Type: ' . @mime_content_type($path));
    header('Content-Length: ' . filesize($path));
    readfile($path);
  }

  /**
   * Processes the CUSTOM_SECTIONS[$index] array as defined in Config.php.
   * Sends CSS and Javascript resources to Smart for inclusion on the current
   * page.
   *
   * @return StaticResource[] HTML resources defined (and present in the database).
   */
  static function addCustomSections($index) {
    $css = Config::CUSTOM_SECTIONS[$index]['css'] ?? [];
    $js =  Config::CUSTOM_SECTIONS[$index]['js'] ?? [];
    $combined = array_merge($css, $js);
    foreach ($combined as $name) {
      $sr = StaticResource::getLocalizedByName($name);
      Smart::addStaticResource($sr);
    }

    $html = Config::CUSTOM_SECTIONS[$index]['html'] ?? [];
    $result = [];
    foreach ($html as $name) {
      $sr = StaticResource::getLocalizedByName($name);
      if ($sr) {
        $result[] = $sr;
      }
    }
    return $result;
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
