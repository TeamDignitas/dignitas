<?php

class Str {

  static function endsWith($string, $substring) {
    $lenString = strlen($string);
    $lenSubstring = strlen($substring);
    $endString = substr($string, $lenString - $lenSubstring, $lenSubstring);
    return $endString == $substring;
  }

  static function startsWith($string, $substring) {
    $startString = substr($string, 0, strlen($substring));
    return $startString == $substring;
  }

  static function getCharAt($s, $index) {
    return mb_substr($s, $index, 1);
  }

  // assumes that the string is trimmed
  static function capitalize($s) {
    if (!$s) {
      return $s;
    }
    return mb_strtoupper(self::getCharAt($s, 0)) . mb_substr($s, 1);
  }

  static function randomString($length = 10) {
    $alphabet = '0123456789abcdefghijklmnopqrstuvwxyz';
    $sigma = strlen($alphabet);

    $result = '';
    while ($length--) {
      $result .= $alphabet[rand(0, $sigma - 1)];
    }
    return $result;
  }

  static function formatNumber($n, int $decimals = 0) {
    $l = localeconv();
    return number_format($n, $decimals, $l['decimal_point'], $l['thousands_sep']);
  }

  static function shorten($s, $maxLength) {
    $l = mb_strlen($s);
    if ($l >= $maxLength + 3) {
      return mb_substr($s, 0, $maxLength - 3) . '...';
    }
    return $s;
  }

  /**
   * Shortens a string without retaining half words.
   * @param string $s The string to shorten.
   * @param int $maxLen The maximum length $s can have before shortening occurs.
   * @param int $targetLen The desired len after shortening (excluding the ellipsis).
   */
  static function shortenPhrase(string $s, int $maxLen, int $targetLen) {
    if (mb_strlen($s) > $maxLen) {
      $pos = $targetLen;
      while ($s[$pos] != ' ') {
        $pos--;
      }
      $s = substr($s, 0, $pos) . '...';
    }
    return $s;
  }

  /**
   * Shortens a string and makes it URL-friendly.
   */
  static function urlize(string $s, int $maxLen = PHP_INT_MAX) {
    $s = strtolower(Str::flatten($s));
    $s = str_replace(['"', "'"], '', $s);
    $s = preg_replace("/[^A-Za-z0-9-]/", '-', $s);
    $s = preg_replace('/\-+/', '-', $s);
    if (strlen($s) > $maxLen) {
      while ($s[$maxLen] != '-') {
        $maxLen--;
      }
      $s = substr($s, 0, $maxLen);
    }
    $s = trim($s, '-');
    return $s;
  }

  /**
   * Transliterates Unicode to ASCII.
   **/
  static function flatten($s) {
    return iconv('UTF-8', 'ASCII//TRANSLIT', $s);
  }

  /**
   * @return boolean true if $s is a well-formed, relative URL, false otherwise.
   */
  static function isRelativeUrl($s) {
    if (Str::startsWith($s, '#')) {
      return false; // don't touch fragment-only URLs
    }
    $scheme = parse_url($s, PHP_URL_SCHEME);
    return $scheme === null; // false is reserved for malformed URLs
  }
}
