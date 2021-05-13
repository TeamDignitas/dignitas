<?php

class HelpCategory extends Proto {

  // HelpCategoryT object for this category in the current locale
  private $helpCategoryT = null;

  function getViewUrl() {
    return Router::link('help/index') . '/' . $this->getPath();
  }

  function getTranslation() {
    $locale = LocaleUtil::getCurrent();
    if (!$this->helpCategoryT) {
      $this->helpCategoryT =
        HelpCategoryT::get_by_categoryId_locale($this->id, $locale);
      if (!$this->helpCategoryT) {
        $this->helpCategoryT =
          HelpCategoryT::get_by_categoryId_locale($this->id, Config::DEFAULT_LOCALE);
      }
    }
    return $this->helpCategoryT;
  }

  function getName() {
    return $this->getTranslation()->name;
  }

  function getPath() {
    return $this->getTranslation()->path;
  }

  /**
   * Loads the HelpCategoryT object for every locale. If a HelpCategoryT does
   * not exist for a locale, creates it (in memory only).
   * @return array An array mapping every locale to a HelpCategoryT.
   */
  function getAllTranslations() {
    $results = [];
    foreach (LocaleUtil::getAll() as $code => $ignored) {
      $results[$code] = HelpCategoryT::loadOrCreate((int)$this->id, $code);
    }
    return $results;
  }

  static function getByPath($path) {
    $hct = HelpCategoryT::get_by_path($path);
    return $hct
      ? HelpCategory::get_by_id($hct->categoryId)
      : null;
  }

  static function loadAll() {
    return Model::factory('HelpCategory')
      ->order_by_asc('rank')
      ->find_many();
  }

  function getPages() {
    return Model::factory('HelpPage')
      ->where('categoryId', $this->id)
      ->order_by_asc('rank')
      ->find_many();
  }

  function delete() {
    Log::warning('Deleted help category %d', $this->id);
    HelpCategoryT::delete_all_by_categoryId($this->id);
    parent::delete();
  }

}
