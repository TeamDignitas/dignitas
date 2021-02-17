<?php

class OS {

  static function errorAndExit($msg, $exitCode = 1) {
    Log::error("ERROR: $msg");
    exit($exitCode);
  }

  static function execute($command, &$output) {
    Log::debug('Executing %s', $command);
    exec($command, $output, $exitCode);
    $output = implode("\n", $output);
    return $exitCode;
  }

  static function executeAndAssert($command, &$output = null) {
    $exitCode = self::execute($command, $output);
    if ($exitCode) {
      Log::error('Output: %s', $output);
      self::errorAndExit("Failed command: $command (code $exitCode)");
    }
  }

}
