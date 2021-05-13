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

  /**
   * Loads the HelpPageT object for every locale. If a HelpPageT does
   * not exist for a locale, creates it (in memory only).
   * @return array An array mapping every locale to a HelpPageT.
   */
  function getAllTranslations() {
    $results = [];
    foreach (LocaleUtil::getAll() as $code => $ignored) {
      $results[$code] = HelpPageT::loadOrCreate((int)$this->id, $code);
    }
    return $results;
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

  /**
   * Rel alternates are trickier for help pages, because we need to also
   * localize the path. Router.php isn't aware of this, but we are.
   */
  function updateRelAlternates() {
    foreach (Config::LOCALES as $locale => $ignored) {
      if ($locale != LocaleUtil::getCurrent()) {
        $hpt = HelpPageT::get_by_pageId_locale($this->id, $locale);
        if ($hpt) {
          $url = Router::getRelAlternate($locale);
          // the path is the string following the rightmost slash
          $url = substr($url, 0, 1 + strrpos($url, '/')) . $hpt->path;
          Router::updateRelAlternate($locale, $url);
        }
      }
    }
  }

  function delete() {
    Log::warning('Deleted help page %d', $this->id);
    HelpPageT::delete_all_by_pageId($this->id);
    parent::delete();
  }

}
