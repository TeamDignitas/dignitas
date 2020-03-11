<?php

/**
 * Wrapper around the third-party Markdown parsing library.
 */

require_once 'third-party/Parsedown.php';

class Markdown {

  /**
   * Converts Markdown to HTML.
   *
   * @param string $s String in Markdown syntax.
   */
  static function parse($s) {
    $s = trim($s);
    $pd = new Parsedown();
    return sprintf('<div class="markdown">%s</div>', $pd->text($s));
  }

}
