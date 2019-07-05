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

  // Returns the short or long localized name of the $n-th month.
  static function getMonthName($n, $shortMonthName = false) {
    $format = $shortMonthName ? '%b' : '%B';
    return strftime($format, mktime(0, 0, 0, $n));
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
   * $date: formatted as YYYY-MM-DD, e.g. '2019-04-24'. Possibly partial.
   * @return same as localTimestamp().
   **/
  static function localDate($date, $shortMonthName = false, $default = '') {
    $monthFormat = $shortMonthName ? '%b' : '%B';
    list($year, $month, $day) = explode('-', $date);
    $date = str_replace('-00', '-01', $date); // handle partial dates
    $timestamp = strtotime($date);

    if ($year == '0000') {
      return $default;
    } else if ($month == '00') {
      return $year;
    } else if ($day == '00') {
      $format = $shortMonthName ? _('%b %Y') : _('%B %Y');
      return trim(strftime($format, $timestamp));
    } else {
      $format = $shortMonthName ? _('%b %e, %Y') : _('%B %e, %Y');
      return trim(strftime($format, $timestamp));
    }
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
    if ($date == '0000-00-00') {
      return null;
    }

    $date = str_replace('-00', '-01', $date); // handle partial dates
    return (int)((time() - strtotime($date)) / 86400);
  }

  // Converts the values to a YYYY-MM-DD string. Certain value combinations may be empty.
  // Returns zeroes for empty parts, null for invalid combinations or incorrect dates.
  static function partialDate($year, $month, $day) {
    if ((!$year && ($month || $day)) ||
        ($year && !$month && $day)) {
      return null;
    }

    if (($day && !checkdate((int)$month, (int)$day, (int)$year)) ||
        ($month && !checkdate((int)$month, 1, (int)$year)) ||
        ($year && !checkdate(1, 1, (int)$year))) {
      return null;
    }

    return sprintf('%04d-%02d-%02d', $year, $month, $day);
  }

}
