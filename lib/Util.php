<?php

class Util {

  static function assertNotLoggedIn() {
    if (User::getActive()) {
      Util::redirect(Core::getWwwRoot());
    }
  }

  static function redirect($location, $statusCode = 303) {
    FlashMessage::saveToSession();
    header("Location: $location", true, $statusCode);
    exit;
  }

}
