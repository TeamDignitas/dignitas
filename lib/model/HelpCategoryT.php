<?php

class HelpCategoryT extends Proto {

  static function loadOrCreate(int $categoryId, string $locale) {
    $hct = self::get_by_categoryId_locale($categoryId, $locale);
    if (!$hct) {
      $hct = Model::factory('HelpCategoryT')->create();
      $hct->categoryId = $categoryId;
      $hct->locale = $locale;
    }
    return $hct;
  }

  function isEmpty() {
    return !$this->name && !$this->path;
  }
}
