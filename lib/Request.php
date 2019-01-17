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

  /* Use when the parameter is expected to have array type. */
  static function getArray($name) {
    $val = self::get($name, []);
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
   * Returns true if this script is running in response to a web request,
   * false otherwise.
   */
  static function isWeb() {
    return isset($_SERVER['REMOTE_ADDR']);
  }

  static function isAjax() {
    return isset($_SERVER['REQUEST_URI']) &&
      Str::startsWith($_SERVER['REQUEST_URI'], Core::getWwwRoot() . 'ajax/');
  }

  static function getFullServerUrl() {
    $protocol = Config::PROTOCOL;
    $host = $_SERVER['SERVER_NAME'];
    $port =  $_SERVER['SERVER_PORT'];
    $path = Core::getWwwRoot();

    return ($port == '80')
      ? "{$protocol}://{$host}{$path}"
      : "{$protocol}://{$host}:{$port}{$path}";
  }

}
