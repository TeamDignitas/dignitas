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

  /* Reads a request parameter. Cleans up string and array values. */
  static function get($name, $default = null) {
    return $_REQUEST[$name] ?? $default;
  }

  /* Reads a file record from $_FILES. */
  static function getFile($name, $default = null) {
    return $_FILES[$name] ?? $default;
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
   * Reads an uploaded image file. Returns an array consisting of a status
   * code (one of the UPLOAD_* constants) and possibly the temporary file name
   * and the file extension.
   **/
  static function getImage($name) {
    $rec = self::getFile($name);

    if (!$rec ||
        !$rec['size'] ||
        ($rec['error'] == UPLOAD_ERR_NO_FILE)) {
      return [ 'status' => self::UPLOAD_NONE ];

    } else if ($rec['error'] == UPLOAD_ERR_INI_SIZE ||
               $rec['error'] == UPLOAD_ERR_FORM_SIZE ||
               $rec['size'] > Config::MAX_IMAGE_SIZE) {
      return [ 'status' => self::UPLOAD_TOO_LARGE ];

    } else if (!isset(Config::IMAGE_MIME_TYPES[$rec['type']])) {
      return [ 'status' => self::UPLOAD_BAD_MIME_TYPE ];

    } else if ($rec['error'] != UPLOAD_ERR_OK) {
      return [ 'status' => self::UPLOAD_OTHER_ERROR ];

    } else {
      // actual upload
      return [
        'status' => self::UPLOAD_OK,
        'tmpImageName' => $rec['tmp_name'],
        'extension' => Config::IMAGE_MIME_TYPES[$rec['type']],
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
