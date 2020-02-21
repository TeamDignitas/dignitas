<?php

class Util {

  static function assertNotLoggedIn() {
    if (User::getActive()) {
      FlashMessage::add(_('info-already-logged-in'));
      Util::redirectToHome();
    }
  }

  /* Returns $obj->$prop for every $obj in $a */
  static function objectProperty($a, $prop) {
    $results = [];
    foreach ($a as $obj) {
      $results[] = $obj->$prop;
    }
    return $results;
  }

  static function redirect($location, $statusCode = 303) {
    FlashMessage::saveToSession();
    header("Location: $location", true, $statusCode);
    exit;
  }

  static function redirectToRoute($route) {
    self::redirect(Router::link($route));
  }

  static function redirectToHome() {
    self::redirect(Config::URL_PREFIX);
  }

  static function redirectToLogin() {
    if (!empty($_POST)) {
      Session::set('postData', $_POST);
    }
    FlashMessage::add(_('info-must-log-in'), 'warning');
    Session::set('REAL_REFERRER', $_SERVER['REQUEST_URI']);
    self::redirectToRoute('auth/login');
  }

  // Redirects to the same page, stripping any GET parameters but preserving
  // any slash-delimited arguments.
  static function redirectToSelf() {
    $uri = $_SERVER['REQUEST_URI'];
    $path = parse_url($uri, PHP_URL_PATH);
    self::redirect($path);
  }

  // Looks up the referrer in $_REQUEST, then in $_SESSION, then in $_SERVER.
  // We sometimes need to pass the referrer in $_SESSION because PHP redirects
  // (in particular redirects to the login page) lose the referrer.
  static function getReferrer() {
    $referrer = Request::get('referrer');

    if ($referrer) {
      Session::unsetVar('REAL_REFERRER');
    } else {
      $referrer = Session::get('REAL_REFERRER') ?? $_SERVER['HTTP_REFERER'] ?? null;
    }

    return $referrer;
  }

  static function getUploadMimeTypes() {
    $extensions = Config::UPLOAD_SPECS['Attachment']['extensions'];
    $extMap = array_flip(Config::MIME_TYPES);

    $results = [];
    foreach ($extensions as $ext) {
      $results[] = $extMap[$ext];
    }
    return $results;
  }

}
