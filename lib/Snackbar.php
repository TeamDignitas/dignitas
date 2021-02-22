<?php

/**
 * Snackbars modeled after https://material.io/components/snackbars with
 * a few exceptions:
 *  * They can stack.
 *
 */

class Snackbar {
  // an array of text messages
  private static $messages = [];

  /**
   * Adds a message to a message queue for later processing.
   */
  static function add($message) {
    if (Request::isWeb()) {
      self::$messages[] = $message;
    }
  }

  static function getAll() {
    return self::$messages;
  }

  static function saveToSession() {
    if (count(self::$messages)) {
      Session::set('snackbars', self::$messages);
    }
  }

  static function restoreFromSession() {
    if ($messages = Session::get('snackbars')) {
      self::$messages = $messages;
      Session::unsetVar('snackbars');
    }
  }
}
