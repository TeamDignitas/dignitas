<?php

// Utilities for objects that have images

class Img {

  private static function getSubdirectory($obj) {
    return strtolower(get_class($obj));
  }

  private static function getImageLocation($obj) {
    if (!$obj->imageExtension || !$obj->id) {
      return '';
    }

    $subdir = self::getSubdirectory($obj);
    $path = sprintf('%simg/%s/%d.%s',
                    Config::SHARED_DRIVE, $subdir, $obj->id, $obj->imageExtension);
    return $path;
  }

  private static function getThumbLocation($obj, $geometry) {
    if (!$obj->imageExtension || !$obj->id) {
      return '';
    }

    if ($obj->imageExtension == 'svg') {
      return self::getImageLocation($obj); // never scale SVGs
    }

    $subdir = self::getSubdirectory($obj);
    $path = sprintf('%simg/%s/thumb-%s/%d.%s',
                    Config::SHARED_DRIVE,
                    $subdir,
                    $geometry,
                    $obj->id,
                    $obj->imageExtension);
    return $path;
  }

  static function getThumbLink($obj, $geometry) {
    $subdir = self::getSubdirectory($obj);
    return sprintf('%s/%d/%s.%s',
                   Router::link("{$subdir}/image"),
                   $obj->id,
                   $geometry,
                   $obj->imageExtension);
  }

  /**
   * If the thumbnail exists, returns its dimensions. If not, returns null
   **/
  static function getThumbSize($obj, $geometry) {
    $file = self::getThumbLocation($obj, $geometry);

    if (!$file || !file_exists($file)) {
      // no image
      return null;
    }

    if ($obj->imageExtension == 'svg') {
      // SVG image
      $rec = self::getSvgSize($file, $geometry);
    } else {
      // non-vector image
      $rec = getimagesize($file);
    }

    return [
      'width' => $rec[0],
      'height' => $rec[1],
    ];
  }

  // delete all image and thumbnail files
  static function deleteImages($obj) {
    $subdir = self::getSubdirectory($obj);
    $cmd = sprintf('rm -f %simg/%s/%d.* %simg/%s/thumb-*/%d.*',
                   Config::SHARED_DRIVE, $subdir, $obj->id,
                   Config::SHARED_DRIVE, $subdir, $obj->id);
    OS::execute($cmd, $ignored);
  }

  private static function copyUploadedImage($obj, $tmpImageName) {
    self::deleteImages($obj);

    $dest = self::getImageLocation($obj);
    @mkdir(dirname($dest), 0777, true);
    copy($tmpImageName, $dest);
  }

  // $class: also serves as index in UPLOAD_SPECS
  static function renderThumb($class, $id, $fileName) {

    @list($geometry, $extension) = explode('.', $fileName, 2);

    $obj = $class::get_by_id($id);

    if (!$obj ||
        !$obj->imageExtension ||
        ($obj->imageExtension != $extension) ||
        !in_array($geometry, Config::UPLOAD_SPECS[$class]['geometries'])) {
      http_response_code(404);
      exit;
    }

    // generate the thumb unless it already exists
    $imgLocation =  self::getImageLocation($obj);
    $thumbLocation = self::getThumbLocation($obj, $geometry);
    if (!file_exists($thumbLocation)) {
      @mkdir(dirname($thumbLocation), 0777, true);
      $cmd = sprintf('convert -geometry %s -sharpen 1x1 %s %s',
                     $geometry, $imgLocation, $thumbLocation);
      OS::execute($cmd, $ignored);
    }

    // now dump it
    if (file_exists($thumbLocation)) {

      $mimeType = array_search($extension, Config::MIME_TYPES);
      header('Content-Type:' . $mimeType);
      header('Content-Length: ' . filesize($thumbLocation));
      readfile($thumbLocation);

    } else {
      http_response_code(404);
    }
  }

  // checks if an image file was uploaded successfully and sets an error if not
  static function validateImageData($imageData) {
    switch ($imageData['status']) {
      case Request::UPLOAD_TOO_LARGE:
        $mb = $imageData['limit'] >> 20;
        return sprintf(_('Maximum image size is %s MB.'), $mb);

      case Request::UPLOAD_BAD_MIME_TYPE:
        return _('Supported image types are JPEG, PNG, GIF and SVG.');

      case Request::UPLOAD_OTHER_ERROR:
        return _('An error occurred while uploading the image.');

      default:
        return null;
    }
  }

  // Saves an object that may contain a new image or a "delete image" command.
  // Assumes all fields in $obj are correctly populated with the exception of
  // imageExtension and possibly id if $obj is new.
  static function saveWithImage($obj, $imageData, $deleteImage) {
    if ($deleteImage) {
      self::deleteImages($obj);
      $obj->imageExtension = '';
    } else if ($imageData['status'] == Request::UPLOAD_OK) {
      $obj->imageExtension = $imageData['extension'];
    } // otherwise leave it unchanged

    $obj->save();

    if (!$deleteImage && ($imageData['status'] == Request::UPLOAD_OK)) {
      self::copyUploadedImage($obj, $imageData['tmpImageName']);
    }

  }

  // Parses the basic cases of ImageMagick-style geometries. Returns an array
  // [ 'width' => $width, 'height' => $height ] where unspecified values are missing.
  static function parseGeometry($geometry) {
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

  // Tries to figure out the SVG size from the file and fit it to $geometry
  // while keeping the aspect ratio. If it fails, then it returns null.
  static function getSvgSize($file, $geometry) {
    $xml = simplexml_load_file($file);
    $attr = $xml->attributes();

    // sometimes there are width and height attributes
    $size = [
      'width' => (float)$attr->width,
      'height' => (float)$attr->height,
    ];

    // sometimes there is a viewBox attribute
    if (!$size['width'] || !$size['height']) {
      $viewBox = (string)$attr->viewBox;
      if ($viewBox) {
        $parts = explode(' ', $viewBox);
        $size = [
          'width' => (float)$parts[2],
          'height' => (float)$parts[3],
        ];
      }
    }

    if (!$size['width'] || !$size['height']) {
      return null;
    }

    // fit it
    $gsize = self::parseGeometry($geometry);
    $scales = [];
    foreach ($gsize as $axis => $value) { // $axis = width and/or height
      $scales[] = $value / $size[$axis];
    }
    $scale = @min($scales) ?? 1.0;

    return [
      (int)($scale * $size['width']),
      (int)($scale * $size['height']),
    ];
  }
}
