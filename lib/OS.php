<?php

class OS {

  static function errorAndExit($msg, $exitCode = 1) {
    Log::error("ERROR: $msg");
    exit($exitCode);
  }

  static function executeAndAssert($command) {
    Log::info("Executing $command");
    exec($command, $output, $exitCode);
    if ($exitCode) {
      Log::error('Output: ' . implode("\n", $output));
      self::errorAndExit("Failed command: $command (code $exitCode)");
    }
  }

}
