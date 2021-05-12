<?php

class HelpPageT extends Proto {
  use MarkdownTrait;

  static function loadOrCreate(int $pageId, string $locale) {
    $hpt = self::get_by_pageId_locale($pageId, $locale);
    if (!$hpt) {
      $hpt = Model::factory('HelpPageT')->create();
      $hpt->pageId = $pageId;
      $hpt->locale = $locale;
    }
    return $hpt;
  }

  function getMarkdownFields() {
    return [ 'contents' ];
  }

  function isEmpty() {
    return !$this->title && !$this->path && !$this->contents;
  }
}
