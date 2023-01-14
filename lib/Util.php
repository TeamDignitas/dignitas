<?php

class Util {

  static function assertNotLoggedIn() {
    if (User::getActive()) {
      Snackbar::add(_('info-already-logged-in'));
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

  /* Replaces [ $x ] by [ $x->id => $x ] */
  static function mapById($objects) {
    $results = [];
    foreach ($objects as $obj) {
      $results[$obj->id] = $obj;
    }
    return $results;
  }

  static function redirect($location, $statusCode = 303) {
    Snackbar::saveToSession();
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
    Snackbar::add(_('info-must-log-in'));
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

  static function getUploadMimeTypes(bool $asJson = false) {
    $extensions = Config::UPLOAD_SPECS['Attachment']['extensions'];
    $extMap = array_flip(Config::MIME_TYPES);

    $results = [];
    foreach ($extensions as $ext) {
      $results[] = $extMap[$ext];
    }
    return $asJson
      ? json_encode($results)
      : $results;
  }

  /**
   * Determines the range of sequential pages to display.
   *
   * @param int $n Total number of pages
   * @param int $k Current page
   * @return int[] An array of two elements, the left and right end of the range.
   *
   * Example: $n = 100, $k = 20 => returns [18, 22]
   */
  static function getPaginationRange($n, $k) {
    // By default display two pages left and two pages right of $k
    $l = max($k - 2, 1);
    $r = min($k + 2, $n);

    // Extend while needed and while there is room to extend on either side.
    while (($r - $l < 4) && ($r - $l < $n - 1)) {
      if ($l == 1) {
        $r++;
      } else {
        $l--;
      }
    }

    // Avoid situations like 1 ... 3 4 5 6 7. Specifically, if the ellipsis
    // would replace just one number, expand the range all the way to the end.
    if ($l <= 3)  {
      $l = 1;
    }
    if ($r >= $n - 2) {
      $r = $n;
    }

    return [$l, $r];
  }

}
