<?php

Log::init();

class Log {
  static $file;
  static $level;

  static function init() {
    self::$file = fopen(Config::LOG_FILE, 'a');
  }

  private static function write($level, $format, $args) {
    if ($level <= Config::LOG_LEVEL) {
      // Find the bottom-most call outside this class
      $trace = debug_backtrace();
      $i = 0;
      while ($trace[$i]['file'] == __FILE__) {
        $i++;
      }

      $file = basename($trace[$i]['file']);
      $line = $trace[$i]['line'];
      $date = date("Y-m-d H:i:s");
      $user = User::getActive();

      fprintf(self::$file, "[{$date}] [{$file}:{$line}] ");
      if ($user) {
        fprintf(self::$file, "[{$user->email}] ");
      }
      if (is_array($format) || is_object($format)) {
        $format = var_export($format, true);
      }
      vfprintf(self::$file, "{$format}\n", $args);
    }
  }

  /**
   * The following functions take printf-style arguments (format + args).
   */
  static function emergency($format, ...$args) {
    self::write(LOG_EMERG, $format, $args);
  }

  static function alert($format, ...$args) {
    self::write(LOG_ALERT, $format, $args);
  }

  static function critical($format, ...$args) {
    self::write(LOG_CRIT, $format, $args);
  }

  static function error($format, ...$args) {
    self::write(LOG_ERR, $format, $args);
  }

  static function warning($format, ...$args) {
    self::write(LOG_WARNING, $format, $args);
  }

  static function notice($format, ...$args) {
    self::write(LOG_NOTICE, $format, $args);
  }

  static function info($format, ...$args) {
    self::write(LOG_INFO, $format, $args);
  }

  static function debug($format, ...$args) {
    self::write(LOG_DEBUG, $format, $args);
  }

}
