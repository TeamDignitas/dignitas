<?php

/**
 * Snackbars modeled after https://material.io/components/snackbars with
 * a few exceptions:
 *  * They can stack.
 *
 */

class Snackbar {
   // an array of [$text, $type] pairs, where $type follows Bootstrap conventions
  private static $messages = [];

  /**
   * Adds messages to a message queue for later processing.
   *
   * @param string $message
   * @param string $type info, success, warning, danger (default)
   */
  static function add($message, $type = 'danger') {
    if (Request::isWeb()) {
      self::$messages[] = [
        'text' => $message,
        'type' => $type
      ];
    }
  }

  /**
   * Adds a more complex message that requires some templating.
   **/
  static function addTemplate($template, $args, $type = 'danger') {
    // TODO this overwrites previously assigned variables. We really should
    // instantiate a separate Smarty.
    if (Request::isWeb()) {
      Smart::assign($args);
      $message = Smart::fetch("alerts/{$template}");
      self::add($message, $type);
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
