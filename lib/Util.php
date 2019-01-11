<?php

class Util {

  static function redirect($location) {
    FlashMessage::saveToSession();
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: $location");
    exit;
  }

}
