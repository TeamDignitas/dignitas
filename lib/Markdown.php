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
    $results = parent::inlineMarkup($excerpt);
    if ($results) {
      self::addUrlPrefix($results);
    }
    return $results;
  }

  protected function blockMarkup($line) {
    $results = parent::blockMarkup($line);
    if ($results) {
      self::addUrlPrefix($results);
    }
    return $results;
  }

  /**
   * Modifies $a['markup'] to prepend URL_PREFIX to URLs.
   */
  static function addUrlPrefix(array &$arr) {
    if (isset($arr['markup'])) {
      preg_match_all(
        '/\b(href|src)=\"([^\"]+)\"/',
        $arr['markup'],
        $matches,
        PREG_OFFSET_CAPTURE);
      foreach (array_reverse($matches[2]) as $rec) {
        if (Str::isRelativeUrl($rec[0])) {
          $arr['markup'] = substr_replace($arr['markup'], Config::URL_PREFIX, $rec[1], 0);
        }
      }
    }
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
    $anchor = sprintf('<a href="#%s"><span class="material-icons">insert_link</span></a>', $text);
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
