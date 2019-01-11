<?php

/**
 * This class reads request parameters.
 **/
class Request {

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

  /* Returns an array of values from a parameter in CSV format */
  static function getCsv($name) {
    return explode(',', self::get($name, []));
  }

  /* Use when the parameter is expected to have array type. */
  static function getArray($name) {
    $val = self::get($name);
    return empty($val) ? [] : $val;
  }

  /**
   * Use when the parameter is encoded JSON.
   * Note that the JSON string must be decoded before cleanup. Otherwise entities like „”
   * can be replaced with "", which will corrupt the JSON string.
   **/
  static function getJson($name, $default = null, $assoc = false) {
    if (!array_key_exists($name, $_REQUEST)) {
      return $default;
    } else {
      $json = $_REQUEST[$name];
      return json_decode($json, $assoc);
    }
  }

  /**
   * Returns true if this script is running in response to a web request, false
   * otherwise.
   */
  static function isWeb() {
    return isset($_SERVER['REMOTE_ADDR']);
  }

}
