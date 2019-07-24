<?php

/**
 * This class reads request parameters.
 **/
class Request {

  const UPLOAD_NONE = 0;          // no file was uploaded
  const UPLOAD_OK = 1;            // upload successful
  const UPLOAD_TOO_LARGE = 2;     // file size exceeds limit
  const UPLOAD_BAD_MIME_TYPE = 3; // file has incorrect MIME type
  const UPLOAD_OTHER_ERROR = 4;

  static function init() {
    // PHP silently discards files exceeding the post limit, leaving no trace
    // in $_FILES and in $_POST.
    // https://stackoverflow.com/questions/7852910/php-empty-post-and-files-when-uploading-larger-files
    $length = $_SERVER['CONTENT_LENGTH'] ?? 0;
    if (empty($_POST) &&
        empty($_FILES) &&
        ((int)$length > 0)) {
      http_response_code(404);
      throw new Exception(
        'PHP discarded POST data because it was too large. ' .
        'We promise to handle this error gracefully one day.');
    }
  }

  /* Reads a request parameter. Cleans up string and array values. */
  static function get($name, $default = null) {
    return $_REQUEST[$name] ?? $default;
  }

  /* Reads a present-or-not parameter (checkbox, button etc.). */
  static function has($name) {
    return array_key_exists($name, $_REQUEST);
  }

  /* Use when the parameter is expected to have array type. */
  static function getArray($name) {
    return self::get($name, []);
  }

  /* Use when the parameter is JSON-encoded. */
  static function getJson($name, $default = null, $assoc = false) {
    if (!array_key_exists($name, $_REQUEST)) {
      return $default;
    } else {
      $json = $_REQUEST[$name];
      return json_decode($json, $assoc);
    }
  }

  /**
   * Reads an uploaded file. Returns an array consisting of a status code (one
   * of the UPLOAD_* constants) and possibly the temporary file name and the
   * file extension.
   **/
  static function getFile($name, $class) {
    $rec = $_FILES[$name] ?? null;
    $extension = Config::MIME_TYPES[$rec['type']] ?? null;
    $allowedExtensions = Config::UPLOAD_SPECS[$class]['extensions'];
    $limit = Config::UPLOAD_SPECS[$class]['limit'];

    if (!$rec ||
        !$rec['size'] ||
        ($rec['error'] == UPLOAD_ERR_NO_FILE)) {
      return [ 'status' => self::UPLOAD_NONE ];

    } else if ($rec['error'] == UPLOAD_ERR_INI_SIZE ||
               $rec['error'] == UPLOAD_ERR_FORM_SIZE ||
               $rec['size'] > $limit) {
      return [
        'status' => self::UPLOAD_TOO_LARGE,
        'limit' => $limit,
      ];

    } else if (!$extension || !in_array($extension, $allowedExtensions)) {
      return [ 'status' => self::UPLOAD_BAD_MIME_TYPE ];

    } else if ($rec['error'] != UPLOAD_ERR_OK) {
      return [ 'status' => self::UPLOAD_OTHER_ERROR ];

    } else {
      // actual upload
      return [
        'status' => self::UPLOAD_OK,
        'tmpFileName' => $rec['tmp_name'],
        'extension' => $extension,
      ];
    }
  }

  /**
   * Returns true if this script is running in response to a web request,
   * false otherwise.
   */
  static function isWeb() {
    return isset($_SERVER['REMOTE_ADDR']);
  }

  static function isAjax() {
    return isset($_SERVER['REQUEST_URI']) &&
      Str::startsWith($_SERVER['REQUEST_URI'], Config::URL_PREFIX . 'ajax/');
  }

}
