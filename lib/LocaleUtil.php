<?php

class LocaleUtil {
  const COOKIE_NAME = 'language';

  static function init() {
    self::set(self::getCurrent());
  }

  static function getAll() {
    return Config::LOCALES;
  }

  // Returns the locale as dictated, in order of priority, by
  // 1. user cookie
  // 2. config file
  static function getCurrent() {
    $locale = Config::DEFAULT_LOCALE;

    $cookie = $_COOKIE[self::COOKIE_NAME] ?? null;
    if ($cookie && isset(Config::LOCALES[$cookie])) { // sanity check
      $locale = $cookie;
    }

    return $locale;
  }

  // Returns the locale with the encoding stripped off, e.g. en_US instead of en_US.utf8
  static function getShort() {
    $l = self::getCurrent();
    return explode('.', $l)[0];
  }

  static function getDisplayName($locale) {
    return Config::LOCALES[$locale] ?? '';
  }

  private static function set($locale) {
    mb_internal_encoding('UTF-8');

    // workaround for Windows lovers
    if (PHP_OS_FAMILY == 'Windows') {
      putenv("LC_ALL=$locale");
    }

    setlocale(LC_ALL, $locale);
    $domain = "messages";
    bindtextdomain($domain, Config::ROOT . '/locale');
    bind_textdomain_codeset($domain, 'UTF-8');
    textdomain($domain);
  }

  // changes the locale and stores it in a cookie
  static function change($id) {
    if (!isset(Config::LOCALES[$id])) {
      return;
    }

    // delete the existing cookie if it matches the config value
    if ($id == Config::DEFAULT_LOCALE) {
      Session::unsetCookie(self::COOKIE_NAME);
    } else {
      setcookie(self::COOKIE_NAME, $id, time() + Session::ONE_YEAR_IN_SECONDS, '/');
    }

    self::set($id);
  }

  static function getSelect2Locale() {
    $locale = self::getCurrent();
    return Config::SELECT2_LOCALES[$locale] ?? null;
  }

  static function getDatePickerLocale() {
    $locale = self::getCurrent();
    return Config::DATEPICKER_LOCALES[$locale][0] ?? null;
  }

  static function getDatePickerFormat() {
    $locale = self::getCurrent();
    return Config::DATEPICKER_LOCALES[$locale][1] ?? null;
  }

}
