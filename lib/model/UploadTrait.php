<?php

// Method implementations for objects that have a matching uploaded file on
// the shared drive. These objects should have a fileExtension field.

trait UploadTrait {

  // Traits cannot have constants because PHP.
  // <shared_drive>/upload/<object_class>/<geometry>/<shard>/<id>.<extension>
  static $FILE_PATTERN = '%supload/%s/%s/%d/%d.%s';

  // <shared_drive>/upload/<object_class>/*/<shard>/<id>.*
  static $DELETE_COMMAND = 'rm -rf %supload/%s/*/%d/%d.*';

  // <route>/<id>/<geometry>.<extension>
  static $URL_PATTERN = '%s/%d/%s.%s';

  static $FULL_GEOMETRY = 'full';

  // subdirectory on the shared drive where the file and its thumbs reside
  abstract function getFileSubdirectory();

  // route to call when viewing the file or its thumbs
  abstract function getFileRoute();

  private function getShard() {
    return (int)($this->id / 1000);
  }

  // sometimes thumbnails have a different extension than the original file
  // (e.g. PDF files have JPG thumbnails)
  private function getExtension($geometry) {
    if ($geometry == self::$FULL_GEOMETRY) {
      return $this->fileExtension;
    } else {
      return Config::THUMB_EXTENSIONS[$this->fileExtension] ?? $this->fileExtension;
    }
  }

  function getFileLocation($geometry) {
    if (!$this->fileExtension || !$this->id) {
      return '';
    }

    if ($this->fileExtension == 'svg') {
      $geometry = self::$FULL_GEOMETRY; // never scale SVGs
    }

    return sprintf(self::$FILE_PATTERN,
                   Config::SHARED_DRIVE,
                   $this->getFileSubdirectory(),
                   $geometry,
                   $this->getShard(),
                   $this->id,
                   $this->getExtension($geometry));
  }

  function getFileUrl($geometry) {
    return sprintf(self::$URL_PATTERN,
                   Router::link($this->getFileRoute()),
                   $this->id,
                   $geometry,
                   $this->getExtension($geometry));
  }

  /**
   * If the file exists, returns its width and height. If not, returns []
   **/
  function getFileSize($geometry) {
    $this->ensureThumbnail($geometry);
    $file = $this->getFileLocation($geometry);

    if (!$file || !file_exists($file)) {
      // no file
      return [];
    } else if ($this->fileExtension == 'svg') {
      // SVG image
      return $this->getSvgSize($file, $geometry);
    } else {
      // non-vector image
      // TODO: or PDF
      $rec = getimagesize($file);
      return [
        'width' => $rec[0],
        'height' => $rec[1],
      ];
    }
  }

  // delete the uploaded file and all its thumbnails
  function deleteFiles() {
    $subdir = $this->getFileSubdirectory();
    $cmd = sprintf(self::$DELETE_COMMAND,
                   Config::SHARED_DRIVE,
                   $this->getFileSubdirectory(),
                   $this->getShard(),
                   $this->id);
    OS::execute($cmd, $ignored);
  }

  private function copyUploadedFile($tmpFileName) {
    $this->deleteFiles();

    $dest = $this->getFileLocation(self::$FULL_GEOMETRY);
    @mkdir(dirname($dest), 0777, true);
    copy($tmpFileName, $dest);
  }

    // generate the thumb unless it already exists
  private function ensureThumbnail($geometry) {
    $thumbLocation = $this->getFileLocation($geometry);
    if (!file_exists($thumbLocation)) {
      $origLocation =  $this->getFileLocation(self::$FULL_GEOMETRY);
      @mkdir(dirname($thumbLocation), 0777, true);
      $cmd = sprintf('convert -resize %s -sharpen 1x1 %s[0] %s',
                     $geometry, $origLocation, $thumbLocation);
      OS::execute($cmd, $ignored);
    }
  }

  // geometry will be derived from the filename
  function renderFile($fileName) {

    @list($geometry, $extension) = explode('.', $fileName, 2);
    $class = get_class(); // serves as index in UPLOAD_SPECS

    // sanity checks
    if (!$this->fileExtension ||
        ($this->getExtension($geometry) != $extension) ||
        !in_array($geometry, Config::UPLOAD_SPECS[$class]['geometries'])) {
      http_response_code(404);
      exit;
    }

    // now dump it
    $this->ensureThumbnail($geometry);
    $thumbLocation = $this->getFileLocation($geometry);
    if (file_exists($thumbLocation)) {

      $mimeType = array_search($extension, Config::MIME_TYPES);
      header('Content-Type:' . $mimeType);
      header('Content-Length: ' . filesize($thumbLocation));
      readfile($thumbLocation);

    } else {
       // no thumbnail and we could not generate it
      http_response_code(404);
    }
  }

  // checks if a file was uploaded successfully and returns an error message if not
  static function validateFileData($fileData) {
    switch ($fileData['status']) {
      case Request::UPLOAD_TOO_LARGE:
        $mb = $fileData['limit'] >> 20;
        return sprintf(_('Maximum file size is %s MB.'), $mb);

      case Request::UPLOAD_BAD_MIME_TYPE:
        // TODO get extensions from the Config file
        return _('Supported file types are JPEG, PNG, GIF and SVG.');

      case Request::UPLOAD_OTHER_ERROR:
        return _('An error occurred while uploading the file.');

      default:
        return null;
    }
  }

  // Saves an object that may contain a new uploaded file or a "delete
  // uploaded file" command.  Assumes all fields in $this are correctly
  // populated with the exception of fileExtension and possibly id if $this is
  // new.
  function saveWithFile($fileData, $deleteFile) {
    if ($deleteFile) {
      $this->deleteFiles();
      $this->fileExtension = '';
    } else if ($fileData['status'] == Request::UPLOAD_OK) {
      $this->fileExtension = $fileData['extension'];
    } // otherwise leave it unchanged

    $this->save();

    if (!$deleteFile && ($fileData['status'] == Request::UPLOAD_OK)) {
      $this->copyUploadedFile($fileData['tmpFileName']);
    }

  }

  // Parses the basic cases of ImageMagick-style geometries. Returns an array
  // [ 'width' => $width, 'height' => $height ] where unspecified values are missing.
  private function parseGeometry($geometry) {
    $result = [];

    if ($geometry && preg_match('/^(\d*)(x(\d+))?$/', $geometry, $matches)) {
      if (isset($matches[1])) {
        $result['width'] = $matches[1];
      }
      if (isset($matches[3])) {
        $result['height'] = $matches[3];
      }
    }

    return $result;
  }

  // Tries to figure out the aspect ratio from the file or assumes a square
  // aspect. Then returns the width and height fitted to $geometry.
  private function getSvgSize($file, $geometry) {
    $xml = simplexml_load_file($file);
    $attr = $xml->attributes();

    if ((float)$attr->width && (float)$attr->height) {

      // sometimes there are width and height attributes
      $ratio = (float)$attr->width / (float)$attr->height;

    } else if ((string)$attr->viewBox) {

      // sometimes there is a viewBox attribute
      $parts = explode(' ', (string)$attr->viewBox);
      $ratio = (float)$parts[2] / (float)$parts[3];

    } else {

      // assume a square aspect
      $ratio = 1.0;

    }

    // fit it
    $g = $this->parseGeometry($geometry);
    if (isset($g['height']) &&
        (!isset($g['width']) || ($g['height'] * $ratio < $g['width']))) {
      return [
        'width' => (int)($g['height'] * $ratio),
        'height' => $g['height'],
      ];
    } else if ($g['width']) {
      return [
        'width' => $g['width'],
        'height' => (int)($g['width'] / $ratio),
      ];
    } else {
      return []; // no recommendations, e.g. if $geometry is 'full'
    }
  }
}
