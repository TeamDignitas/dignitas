<?php

/**
 * Methods for classes that may include URLs worth archiving:
 *   * Link (statement sources, relation sources etc.);
 *   * Statement (the context field);
 *   * ... and others.
 */

trait ArchivableLinksTrait {

  // Based on @imme_emosol's version from https://mathiasbynens.be/demo/url-regex,
  // but forbidding <>'"() in order to correctly match Markdown-style links
  // [text](url) and HTML-style links <a href="url">text</a>.
  // Note: we care about absolute URLs only.
  static $URL_REGEX = "@(https?|ftp)://(-\.)?([^\s<>'\"()/?\.#-]+\.?)+(/[^\s<>'\"()]*)?@iS";

  /**
   * @return array An array of URLs worth archiving for this object.
   */
  abstract function getArchivableUrls();

  /**
   * @return array An array of URLs worth archiving for this string.
   */
  static function extractArchivableUrls(string $str) {
    preg_match_all(self::$URL_REGEX, $str, $matches, PREG_PATTERN_ORDER);
    return $matches[0];
  }

}
