<?php

class LocaleUtil {
  const COOKIE_NAME = 'language';

  private static $current;

  // Initializes the locale, in order of priority, from
  // 1. user cookie
  // 2. config file
  static function init() {
    self::$current = Config::DEFAULT_LOCALE;

    $cookie = $_COOKIE[self::COOKIE_NAME] ?? null;
    if ($cookie && isset(Config::LOCALES[$cookie])) { // sanity check
      self::$current = $cookie;
    }

    self::configure();
  }

  static function getAll() {
    return Config::LOCALES;
  }

  static function getCurrent() {
    return self::$current;
  }

  // Returns the locale with the encoding stripped off, e.g. en_US instead of en_US.utf8
  static function getShort() {
    $l = self::getCurrent();
    return explode('.', $l)[0];
  }

  static function getDisplayName($locale) {
    return Config::LOCALES[$locale] ?? '';
  }

  private static function configure() {
    $l = self::$current;
    mb_internal_encoding('UTF-8');

    // workaround for Windows lovers
    if (PHP_OS_FAMILY == 'Windows') {
      putenv("LC_ALL=$l");
    }

    setlocale(LC_ALL, $l);
    $domain = 'messages';
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
    self::$current = $id;
    if ($id == Config::DEFAULT_LOCALE) {
      Session::unsetCookie(self::COOKIE_NAME);
    } else {
      setcookie(self::COOKIE_NAME, $id, time() + Session::ONE_YEAR_IN_SECONDS, '/');
    }

    self::configure();
  }

  static function getSelect2Locale() {
    return Config::SELECT2_LOCALES[self::$current] ?? null;
  }

  /**
   * Returns the URL for loyalty documentation, in the current locale.
   */
  static function getLoyaltyUrl() {
    return Config::LOYALTY_URL[self::$current] ?? '';
  }

  /**
   * Returns the URL for search documentation, in the current locale.
   */
  static function getSearchUrl() {
    return Config::SEARCH_URL[self::$current] ?? '';
  }

  /**
   * Returns the URL for verdict documentation, in the current locale.
   */
  static function getVerdictUrl() {
    return Config::VERDICT_URL[self::$current] ?? '';
  }

}
