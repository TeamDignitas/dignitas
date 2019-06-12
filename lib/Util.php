<?php

class Util {

  static function assertNotLoggedIn() {
    if (User::getActive()) {
      FlashMessage::add(_('You are already logged in.'));
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
    FlashMessage::add(_('Please log in to perform this action.'), 'warning');
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

  static function today() {
    return date('Y-m-d');
  }

  /**
   * @return the localized date with long month names (e.g. 'April 24, 2019')
   * or short month names (e.g. 'Apr 24, 2019').
   **/
  static function localTimestamp($timestamp, $shortMonthName = false) {
    $format = $shortMonthName ? _('%b %e, %Y') : _('%B %e, %Y');
    return trim(strftime($format, $timestamp));
  }

  /**
   * $date: formatted as YYYY-MM-DD, e.g. '2019-04-24'.
   * @return same as localTimestamp().
   **/
  static function localDate($date, $shortMonthName = false, $default = '') {
    if (!$date) {
      return $default;
    }
    return self::localTimestamp(strtotime($date), $shortMonthName);
  }

  static function moment($timestamp) {
    $delta = time() - $timestamp;

    $exact = self::localTimestamp($timestamp);
    $exactBracket = " ({$exact})";

    $days = (int)($delta / (60 * 60 * 24));
    if ($days >= 4) {
      return sprintf(_('on %s'), self::localTimestamp($timestamp));
    } else if ($days >= 2) {
      return sprintf(_('%d days ago'), $days) . $exactBracket;
    } else if ($days == 1) {
      return _('yesterday') . $exactBracket;
    }

    $hours = (int)($delta / (60 * 60));
    if ($hours) {
      return sprintf(ngettext('one hour ago', '%d hours ago', $hours), $hours)
        . $exactBracket;
    }

    $minutes = (int)($delta / 60);
    if ($minutes) {
      return sprintf(ngettext('one minute ago', '%d minutes ago', $minutes), $minutes)
        . $exactBracket;
    }

    return sprintf(ngettext('one second ago', '%d seconds ago', $delta), $delta)
      . $exactBracket;
  }

  // $date: formatted as YYYY-MM-DD, e.g. '2019-04-24'
  static function daysAgo($date) {
    if ($date) {
      return (int)((time() - strtotime($date)) / 86400);
    } else {
      return null;
    }
  }
}
