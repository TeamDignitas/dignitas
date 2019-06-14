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

  // assumes $thumbIndex is a valid index in Config::THUMB_SIZES
  private static function getThumbLocation($obj, $thumbIndex) {
    if (!$obj->imageExtension || !$obj->id) {
      return '';
    }

    if ($obj->imageExtension == 'svg') {
      return self::getImageLocation($obj); // never scale SVGs
    }

    $rec = Config::THUMB_SIZES[$thumbIndex];
    $subdir = self::getSubdirectory($obj);
    $path = sprintf('%simg/%s/thumb-%dx%d/%d.%s',
                    Config::SHARED_DRIVE,
                    $subdir,
                    $rec[0],
                    $rec[1],
                    $obj->id,
                    $obj->imageExtension);
    return $path;
  }

  static function getThumbLink($obj, $thumbIndex) {
    $subdir = self::getSubdirectory($obj);
    return sprintf('%s/%d/%d.%s',
                   Router::link("{$subdir}/image"),
                   $obj->id,
                   $thumbIndex,
                   $obj->imageExtension);
  }

  /**
   * If the thumbnail exists, returns its dimensions. If not, falls back to
   * the Config.php values. The two may differ due to differences in aspect.
   **/
  static function getThumbSize($obj, $thumbIndex) {
    $file = self::getThumbLocation($obj, $thumbIndex);

    if (!$file || !file_exists($file)) {
      // no image
      $rec = Config::THUMB_SIZES[$thumbIndex];

    } else if ($obj->imageExtension == 'svg') {
      // SVG image
      $box = Config::THUMB_SIZES[$thumbIndex];
      $rec = self::getSvgSize($file, $box[0], $box[1]);

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

  static function renderThumb($class, $id, $fileName) {

    @list($thumb, $extension) = explode('.', $fileName, 2);

    $obj = $class::get_by_id($id);

    if (!$obj ||
        !$obj->imageExtension ||
        ($obj->imageExtension != $extension) ||
        !isset(Config::THUMB_SIZES[$thumb])) {
      http_response_code(404);
      exit;
    }

    // generate the thumb unless it already exists
    $imgLocation =  self::getImageLocation($obj);
    $thumbLocation = self::getThumbLocation($obj, $thumb);
    if (!file_exists($thumbLocation)) {
      list($width, $height) = Config::THUMB_SIZES[$thumb];
      @mkdir(dirname($thumbLocation), 0777, true);
      $cmd = sprintf('convert -geometry %dx%d -sharpen 1x1 %s %s',
                     $width, $height, $imgLocation, $thumbLocation);
      OS::execute($cmd, $ignored);
    }

    // now dump it
    if (file_exists($thumbLocation)) {

      $mimeType = array_search($extension, Config::IMAGE_MIME_TYPES);
      header('Content-Type:' . $mimeType);
      header('Content-Length: ' . filesize($thumbLocation));
      readfile($thumbLocation);

    } else {
      http_response_code(404);
    }
  }

  // checks if an image file was uploaded successfully and sets an error if not
  static function validateImageStatus($imageStatus) {
    switch ($imageStatus) {
      case Request::UPLOAD_TOO_LARGE:
        $mb = Config::MAX_IMAGE_SIZE >> 20;
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

  // Tries to figure out the SVG size from the file and fit it to $maxWidth x
  // $maxHeight while keeping the aspect ration. If it fails, then it simply
  // returns $maxWidth x $maxHeight.
  static function getSvgSize($file, $maxWidth, $maxHeight) {
    $xml = simplexml_load_file($file);
    $attr = $xml->attributes();
    $rec = [
      (float)$attr->width,
      (float)$attr->height,
    ];

    if ($rec[0] && $rec[1]) {
      // fit it
      $scale = min($maxWidth / $rec[0], $maxHeight / $rec[1]);
      $rec = [ (int)($scale * $rec[0]), (int)($scale * $rec[1]) ];
    } else {
      // unknown size, use the default
      $rec = [ $maxWidth, $maxHeight ];
    }

    return $rec;

  }
}
