<?php

/**
 * Wrapper around the third-party Markdown parsing library.
 */

require_once 'third-party/Parsedown.php';

class Markdown extends Parsedown {

  static function getInstance() {
    static $i = null;

    if ($i === null) {
      $i = new Markdown();
    }
    return $i;
  }

  /**
   * Wrap the resulting markdown in a classed div.
   */
  function text($text) {
    return sprintf('<div class="markdown">%s</div>', parent::text($text));
  }

  /**
   * Add permalinks to header blocks.
   */
  protected function blockHeader($line) {
    $block = parent::blockHeader($line);

    // take the header text and flatten it, shorten it etc.
    $text = $block['element']['text'];
    $text = Str::flatten($text);
    $text = str_replace(' ', '-', $text);
    $text = strtolower($text);
    $text = preg_replace("/[^-a-z0-9]/", '', $text);
    $text = substr($text, 0, 20);

    // add an anchor to the header text
    $anchor = sprintf('<a href="#%s"><i class="icon icon-link"></i></a>', $text);
    $block['element']['text'] .= $anchor;

    // add an ID and class to the header element
    $block['element']['attributes']['id'] = $text;
    $block['element']['attributes']['class'] = 'markdown-heading';

    return $block;
  }

  /**
   * Converts Markdown to HTML.
   *
   * @param string $s String in Markdown syntax.
   */
  static function convert($s) {
    $s = trim($s);
    return self::getInstance()->text($s);
  }

}
