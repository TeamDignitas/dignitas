<?php

/** Date / time utilities **/

class Time {

  private static $YM_DATE_FORMATTER;
  private static $YMD_DATE_FORMATTER;
  private static $YMDHMS_DATE_FORMATTER;

  static function init() {
    self::$YM_DATE_FORMATTER = self::createDateFormatter(_('date-format-ym'));
    self::$YMD_DATE_FORMATTER = self::createDateFormatter(_('date-format-ymd'));
    self::$YMDHMS_DATE_FORMATTER = self::createDateFormatter(
      _('date-format-ymd') . ' hh:mm:ss');
  }

  static function createDateFormatter(string $format) {
    return new IntlDateFormatter(
      LocaleUtil::getCurrent(),
      IntlDateFormatter::NONE,
      IntlDateFormatter::NONE,
      null,
      null,
      $format);
  }

  static function today() {
    return date('Y-m-d');
  }

  /**
   * @return string The localized date with long month names.
   **/
  static function localTimestamp($timestamp, $withTime = true) {
    $fmt = $withTime ? self::$YMDHMS_DATE_FORMATTER : self::$YMD_DATE_FORMATTER;
    return trim($fmt->format($timestamp));
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
      return trim(self::$YM_DATE_FORMATTER->format($timestamp));
    } else {
      return trim(self::$YMD_DATE_FORMATTER->format($timestamp));
    }
  }

  static function moment($timestamp) {
    $delta = time() - $timestamp;

    $days = (int)($delta / (60 * 60 * 24));
    if ($days >= 4) {
      return sprintf(_('time-full-%s'), self::localTimestamp($timestamp, false));
    } else if ($days >= 2) {
      return sprintf(ngettext('time-day-ago-singular', 'time-day-ago-plural-%d', $days),
                     $days);
    } else if ($days == 1) {
      return _('time-yesterday');
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

  // Replaces the 00 values in partial dates with the largest possible values.
  // This is useful when searching for data up to a date which may be partial.
  // Examples: 2010-02-00 -> 2010-02-29, 2010-00-00 -> 2010-12-31
  static function extendPartialDate($date) {
    if (!$date) {
      return ''; // nothing to extend
    }

    list($year, $month, $day) = explode('-', $date);

    if ($month == '00') {
      $month = '12';
    }

    if ($day == '00') {
      // cal_days_in_month() works, but requires the calendar library
      $op = sprintf('%04d-%02d-01 +1 month -1 day', $year, $month);
      $time = strtotime($op);
      return date("Y-m-d", $time);
    }

    return sprintf('%04d-%02d-%02d', $year, $month, $day);
  }

}
