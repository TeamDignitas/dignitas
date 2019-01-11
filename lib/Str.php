<?php

class Str {

  // Make a path portable across OS's
  static function portable($s) {
    return str_replace('/', DIRECTORY_SEPARATOR, $s);
  }

}
