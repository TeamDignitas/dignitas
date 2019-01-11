<?php

class Core {

  private static $wwwRoot;
  private static $rootPath;

  private static $AUTOLOAD_PATHS = [
    'lib',
    'lib' . DIRECTORY_SEPARATOR . 'models',
  ];

  static function autoload($className) {
    foreach (self::$AUTOLOAD_PATHS as $path) {
      $filename = self::getRootPath() . $path . DIRECTORY_SEPARATOR . $className . '.php';
      if (file_exists($filename)) {
        require_once $filename;
        return;
      }
    }
  }

  static function init() {
    spl_autoload_register(); // clear the autoload stack
    spl_autoload_register('Core::autoload', false, true);

    self::defineRootPath();
    self::defineWwwRoot();
    self::requireOtherFiles();
    Smart::init();
  }

  static function defineRootPath() {
    $ds = DIRECTORY_SEPARATOR;
    $fileName = realpath($_SERVER['SCRIPT_FILENAME']);
    $pos = strrpos($fileName, "{$ds}www{$ds}");
    // for scripts
    if ($pos === FALSE) {
      $pos = strrpos($fileName, "{$ds}scripts{$ds}");
    }
    self::$rootPath = substr($fileName, 0, $pos + 1);
  }

  /**
   * Returns the absolute path of the project folder in the file system.
   */
  static function getRootPath() {
    return self::$rootPath;
  }

  /**
   * Returns the home page URL path.
   * Algorithm: compare the current URL with the absolute file name.
   * Travel up both paths until we encounter /www/ in the file name.
   **/
  static function defineWwwRoot() {
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $fileName = realpath($_SERVER['SCRIPT_FILENAME']);
    $pos = strrpos($fileName, '/www/');

    if ($pos === false) {
      $result = '/';     // This shouldn't be the case
    } else {
      $tail = substr($fileName, $pos + strlen('/www/'));
      $lenTail = strlen($tail);
      if ($tail == substr($scriptName, -$lenTail)) {
        $result = substr($scriptName, 0, -$lenTail);
      } else {
        $result = '/';
      }
    }
    self::$wwwRoot = $result;
  }

  /**
   * Returns the root URL for the project (since it could be running in a subdirectory).
   */
  static function getWwwRoot() {
    return self::$wwwRoot;
  }

  static function getImgRoot() {
    return self::getWwwRoot() . 'img';
  }

  static function getCssRoot() {
    return self::getWwwRoot() . 'css';
  }

  static function requireOtherFiles() {
    $root = self::getRootPath();
    require_once Str::portable("{$root}lib/third-party/smarty-3.1.33/Smarty.class.php");
  }
}

Core::init();
