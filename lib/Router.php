<?php

/**
 * Two-way routing:
 * (1) Resolve URLs like path1/path2/arg1/arg2 to PHP files + GET arguments;
 * (2) Resolve links like path/to/file to localized URLs.
 **/

class Router {

  // Reverse route definitions, mapping file names to localized URLs. We
  // prefer this format in order to group all information about one file. We
  // compute the forward routes upon initialization. Files have an implicit
  // .php extension.
  const ROUTES = [
    // auth
    'auth/login' => [
      'en_US.utf8' => 'login',
      'ro_RO.utf8' => 'autentificare',
    ],
    'auth/logout' => [
      'en_US.utf8' => 'logout',
      'ro_RO.utf8' => 'deconectare',
    ],
    'auth/lostPassword' => [
      'en_US.utf8' => 'lost-password',
      'ro_RO.utf8' => 'parola-uitata',
    ],
    'auth/passwordRecovery' => [
      'en_US.utf8' => 'password-recovery',
      'ro_RO.utf8' => 'recuperare-parola',
    ],
    'auth/register' => [
      'en_US.utf8' => 'register',
      'ro_RO.utf8' => 'inregistrare',
    ],

    // helpers
    'helpers/changeLocale' => [
      'en_US.utf8' => 'changeLocale',
    ],

    // statements
    'statement/edit' => [
      'en_US.utf8' => 'edit-statement',
      'ro_RO.utf8' => 'editeaza-afirmatie',
    ],
  ];

  // file => list of parameters expected in the URL (none by default)
  const PARAMS = [
  ];

  private static $fwdRoutes = [];
  private static $relAlternate = [];

  static function init() {
    // compute the forward routes, mapping localized URLs to PHP files
    foreach (self::ROUTES as $file => $locales) {
      foreach ($locales as $url) {
        self::$fwdRoutes[$url] = $file;
      }
    }
  }

  // Executes the corresponding PHP file for this request, then exits.
  // Returns null on routing errors.
  static function route($uri) {
    // strip the GET parameters
    $path = parse_url($uri, PHP_URL_PATH);

    $parts = explode('/', $path);
    $route = array_shift($parts);

    // the route may contain slashes, so try increasingly long segments
    while (!isset(self::$fwdRoutes[$route]) && !empty($parts)) {
      $route .= '/' . array_shift($parts);
    }

    if (isset(self::$fwdRoutes[$route])) {

      // get the PHP file
      $rec = self::$fwdRoutes[$route];
      $file = $rec . '.php';

      // save any alternate versions in case we need to print them in header tags
      self::setRelAlternate($route, $uri);

    } else {

      // fallback: look for a file by the same name under routes/
      $file = $path . '.php';
      $rec = '';

    }

    $absolute = Config::ROOT . 'routes/' . $file;
    if (file_exists($absolute)) {

      // set additional params if the file expects them and the URL has them
      $params = self::PARAMS[$rec] ?? [];
      for ($i = 0; $i < min(count($params), count($parts)); $i++) {
        $_REQUEST[$params[$i]] = urldecode($parts[$i]);
      }

      Log::debug('routing %s to %s', $path, $file);

      require_once $absolute;
      exit;
    }

    Log::debug('no route found for %s', $path);
    return null;
  }

  // Returns a human-readable URL for this file.
  static function link($file, $absolute = false) {
    if (isset(self::ROUTES[$file])) {
      $routes = self::ROUTES[$file];
      $rel = $routes[LocaleUtil::getCurrent()]     // current locale
        ?? $routes[Config::DEFAULT_ROUTING_LOCALE] // or default locale
        ?? '';                                     // or home page
    } else {
      $rel = $file;
    }

    $url = ($absolute ? Config::URL_HOST : '') . Config::URL_PREFIX . $rel;
    return $url;
  }

  // Collect URLs for localized versions of this page.
  // See https://support.google.com/webmasters/answer/189077
  static function setRelAlternate($route, $uri) {
    $routes = self::ROUTES[self::$fwdRoutes[$route]];

    if (count($routes) > 1) {
      foreach ($routes as $locale => $langRoute) {
        $langCode = explode('_', $locale)[0];
        $langUri = substr_replace($uri, $langRoute, 0, strlen($route));
        $langUrl = Config::URL_HOST . Config::URL_PREFIX . $langUri;
        self::$relAlternate[$langCode] = $langUrl;
      }
    }
  }

  static function getRelAlternate() {
    return self::$relAlternate;
  }

}
