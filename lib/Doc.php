<?php

// Utilities for uploaded documents

class Doc {

  const NAME_LENGTH = 20;

  static function render($fileName) {

    @list($name, $extension) = explode('.', $fileName, 2);

    if ((strlen($name) != self::NAME_LENGTH) ||
        !ctype_alnum($name)) {
      http_response_code(404);
      exit;
    }

    $subdir = substr($fileName, 0, 2);
    $fullPath = sprintf('%supload/%s/%s', Config::SHARED_DRIVE, $subdir, $fileName);

    if (!file_exists($fullPath)) {
      http_response_code(404);
      exit;
    }

    $mimeType = array_search($extension, Config::IMAGE_MIME_TYPES);
    header('Content-Type:' . $mimeType);
    header('Content-Length: ' . filesize($fullPath));
    readfile($fullPath);
  }

}
