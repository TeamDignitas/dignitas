<?php

/** Date / time utilities **/

class Time {

  static function today() {
    return date('Y-m-d');
  }

  // Returns the short or long localized name of the $n-th month.
  static function getMonthName($n, $shortMonthName = false) {
    $format = $shortMonthName ? '%b' : '%B';
    return strftime($format, mktime(0, 0, 0, $n));
  }

  /**
   * @return string The localized date with long month names.
   **/
  static function localTimestamp($timestamp, $withTime = true) {
    $format = _('%B %e, %Y');
    if ($withTime) {
      $format .= ' %H:%M:%S';
    }
    return trim(strftime($format, $timestamp));
  }

  /**
   * @param string $date: formatted as YYYY-MM-DD, e.g. '2019-04-24'. Possibly partial.
   * @return string The localized date with long month names.
   **/
  static function localDate($date) {
    list($year, $month, $day) = explode('-', $date);
    $date = str_replace('-00', '-01', $date); // handle partial dates
    $timestamp = strtotime($date);

    if ($year == '0000') {
      return '';
    } else if ($month == '00') {
      return $year;
    } else if ($day == '00') {
      return trim(strftime(_('%B %Y'), $timestamp));
    } else {
      return trim(strftime(_('%B %e, %Y'), $timestamp));
    }
  }

  static function moment($timestamp) {
    $delta = time() - $timestamp;

    $days = (int)($delta / (60 * 60 * 24));
    if ($days >= 4) {
      return sprintf(_('on %s'), self::localTimestamp($timestamp, false));
    } else if ($days >= 2) {
      return sprintf(ngettext('time-day-ago-singular', 'time-day-ago-plural-%d', $days),
                     $days);
    } else if ($days == 1) {
      return _('yesterday');
    }

    $hours = (int)($delta / (60 * 60));
    if ($hours) {
      return sprintf(ngettext('time-hour-ago-singular', 'time-hour-ago-plural-%d', $hours),
                     $hours);
    }

    $minutes = (int)($delta / 60);
    if ($minutes) {
      return sprintf(ngettext('time-minute-ago-singular', 'time-minute-ago-plural-%d', $minutes),
                     $minutes);
    }

    return sprintf(ngettext('time-second-ago-singular', 'time-second-ago-plural-%d', $delta),
                   $delta);
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
