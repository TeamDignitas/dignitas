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

  function __construct() {
    $this->InlineTypes['@'][]= 'Mention';
    $this->inlineMarkerList .= '@';
  }

  /**
   * Wrap the resulting markdown in a classed div.
   */
  function text($text) {
    return sprintf('<div class="markdown">%s</div>', parent::text($text));
  }

  /**
   * Processes an @-mention.
   */
  protected function inlineMention($excerpt) {
    if (!preg_match('/^@(' . User::NICKNAME_REGEXP . ')/u',
                    $excerpt['text'],
                    $matches)) {
      return null;
    }

    $nick = $matches[1];
    $u = User::get_by_nickname($nick);
    if (!$u) {
      return null;
    }

    return [
      'extent' => strlen($matches[0]), // number of characters to advance
      'element' => [
        'name' => 'a',
        'text' => $matches[0],
        'attributes' => [
          'href' => Router::userLink($u),
        ],
      ],
    ];
  }

  /**
   * Prepends URL_PREFIX to relative URLs in <a href="..." and <img src="...".
   * This is aimed at HTML blocks, not at Markdown code like [text](url).
   */
  protected function inlineMarkup($excerpt) {
    $excerpt['text'] = self::addUrlPrefix($excerpt['text']);
    return parent::inlineMarkup($excerpt);
  }

  protected function blockMarkup($line) {
    $line['text'] = self::addUrlPrefix($line['text']);
    return parent::blockMarkup($line);
  }

  protected function blockMarkupContinue($line, $block) {
    $line['body'] = self::addUrlPrefix($line['body']);
    return parent::blockMarkupContinue($line, $block);
  }

  /**
   * Modifies $s to prepend URL_PREFIX to relative URLs.
   */
  static function addUrlPrefix(string $s) {
    preg_match_all(
      '/\b(href|src)=\"([^\"]+)\"/',
      $s,
      $matches,
      PREG_OFFSET_CAPTURE);
    foreach (array_reverse($matches[2]) as $rec) {
      if (Str::isRelativeUrl($rec[0])) {
        $s = substr_replace($s, Config::URL_PREFIX, $rec[1], 0);
      }
    }
    return $s;
  }

  /**
   * Add permalinks to header blocks.
   */
  protected function blockHeader($line) {
    $block = parent::blockHeader($line);

    // take the header text and flatten it, shorten it etc.
    $text = $block['element']['handler']['argument'];
    $text = Str::flatten($text);
    $text = str_replace(' ', '-', $text);
    $text = strtolower($text);
    $text = preg_replace("/[^-a-z0-9]/", '', $text);
    $text = substr($text, 0, 20);

    // add an anchor to the header text
    $anchor = sprintf('<a href="#%s"><span class="material-icons">insert_link</span></a>', $text);
    $block['element']['handler']['argument'] .= $anchor;

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
