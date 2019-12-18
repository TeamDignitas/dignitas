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

  static function markdown($s) {
    $s = trim($s);

    $fileName = tempnam(Config::TMP_DIR, 'dignitas_');
    file_put_contents($fileName, $s);

    OS::execute("marked < {$fileName}", $output);

    @unlink($fileName);

    return $output;
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
   * Splits a multibyte string into characters. If we just extract each
   * character with mb_substr, the complexity is O(N^2). This code runs about
   * twice as fast as the one-liner:
   *
   *   return preg_split('//u', $s, null, PREG_SPLIT_NO_EMPTY);
   */
  static function unicodeExplode($s) {
    $result = [];
    $len = strlen($s);
    $i = 0;

    while ($i < $len) {
      $c = ord($s[$i]);
      if ($c >> 7 == 0) {
        // 0vvvvvvv
        $result[] = $s[$i];
        $i++;
      } else if ($c >> 5 == 6) {
        // 110vvvvv 10vvvvvv
        $result[] = $s[$i] . $s[$i + 1];
        $i += 2;
      } else if ($c >> 4 == 14) {
        // 1110vvvv 10vvvvvv 10vvvvvv
        $result[] = $s[$i] . $s[$i + 1] . $s[$i + 2];
        $i += 3;
      } else if ($c >> 3 == 30) {
        // 11110vvv 10vvvvvv 10vvvvvv 10vvvvvv
        $result[] = $s[$i] . $s[$i + 1] . $s[$i + 2] . $s[$i + 3];
        $i += 4;
      } else {
        // dunno, skip it
        $i++;
      }
    }

    return $result;
  }

}
