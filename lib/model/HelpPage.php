<?php

class HelpPage extends Proto {

  // HelpPageT object for this page in the current locale
  private $helpPageT = null;

  function getObjectType() {
    return Proto::TYPE_HELP_PAGE;
  }

  function getViewUrl() {
    return Router::link('help/index') . '/' . $this->getPath();
  }

  function getHistoryUrl() {
    return Router::link('help/pageHistory') . '/' . $this->id;
  }

  function getCategory() {
    return HelpCategory::get_by_id($this->categoryId);
  }

  function getTranslation() {
    $locale = LocaleUtil::getCurrent();
    if (!$this->helpPageT) {
      $this->helpPageT = HelpPageT::get_by_pageId_locale($this->id, $locale);
      if (!$this->helpPageT) {
        $this->helpPageT = HelpPageT::get_by_pageId_locale($this->id, Config::DEFAULT_LOCALE);
      }
    }
    return $this->helpPageT;
  }

  function getTitle() {
    return $this->getTranslation()->title;
  }

  function getPath() {
    return $this->getTranslation()->path;
  }

  function getContents() {
    return $this->getTranslation()->contents;
  }

  static function getByPath($path) {
    $hpt = HelpPageT::get_by_path($path);
    return $hpt
      ? HelpPage::get_by_id($hpt->pageId)
      : null;
  }

  /**
   * For newly created pages, assigns the next available rank. For existing
   * pages, does nothing.
   */
  function assignNewRank() {
    if (!$this->id) {
      $this->rank = 1 + HelpPage::count_by_categoryId($this->categoryId);
    }
  }

}
