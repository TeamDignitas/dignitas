<?php

class Core {

  const AUTOLOAD_PATHS = [
    'lib',
    'lib/model',
  ];

  // Make a path portable across OS's. This belongs in OS.php, but we need it
  // here *in order to* include OS.php.
  static function portable($s) {
    return str_replace('/', DIRECTORY_SEPARATOR, $s);
  }

  static function autoload($className) {
    foreach (self::AUTOLOAD_PATHS as $path) {
      $filename = Config::ROOT . $path . '/' . $className . '.php';
      if (file_exists($filename)) {
        require_once $filename;
        return;
      }
    }
  }

  static function init() {
    require_once __DIR__ . '/../Config.php';

    spl_autoload_register(); // clear the autoload stack
    spl_autoload_register('Core::autoload', false, true);

    DB::init();
    Session::init();
    Request::init();
    if (!Request::isAjax()) {
      FlashMessage::restoreFromSession();
    }
    Smart::init();
    LocaleUtil::init();
    Router::init();
  }

}

Core::init();
