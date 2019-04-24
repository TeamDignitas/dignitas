<?php

class Util {

  static function assertNotLoggedIn() {
    if (User::getActive()) {
      Util::redirectToHome();
    }
  }

  static function assertLoggedIn() {
    if (!User::getActive()) {
      FlashMessage::add(_('You need to be logged in to perform this action.'));
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

  // Redirects to the same page, stripping any GET parameters but preserving
  // any slash-delimited arguments.
  static function redirectToSelf() {
    $uri = $_SERVER['REQUEST_URI'];
    $path = parse_url($uri, PHP_URL_PATH);
    self::redirect($path);
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
  static function localDate($date, $shortMonthName = false) {
    return self::localTimestamp(strtotime($date), $shortMonthName);
  }

  static function moment($timestamp) {
    $delta = time() - $timestamp;

    $exact = self::localTimestamp($timestamp);

    $days = (int)($delta / (60 * 60 * 24));
    if ($days >= 4) {
      return sprintf(_('on %s'), self::localTimestamp($timestamp));
    } else if ($days >= 2) {
      return sprintf(_('%d days ago') . ' (%s)', $days, $exact);
    } else if ($days == 1) {
      return sprintf(_('yesterday') . ' (%s)', $exact);
    }

    $hours = (int)($delta / (60 * 60));
    if ($hours) {
      return sprintf(ngettext('one hour ago', '%d hours ago', $hours) . ' (%s)',
                     $hours, $exact);
    }

    $minutes = (int)$delta / 60;
    if ($minutes) {
      return sprintf(ngettext('one minute ago', '%d minutes ago', $minutes) . ' (%s)',
                     $minutes, $exact);
    }

    return sprintf(ngettext('one second ago', '%d seconds ago', $delta) . ' (%s)',
                     $delta, $exact);
  }
}
