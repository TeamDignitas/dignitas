<?php

/**
 * Method implementations for objects that have a URL field.
 */
trait UrlTrait {

  function getDisplayUrl() {
    return parse_url($this->url, PHP_URL_HOST);
  }

  function validUrl() {
    return filter_var($this->url, FILTER_VALIDATE_URL);
  }

  /**
   * Builds an array of UrlTrait objects from their IDs and urls.
   */
  static function build($ids, $urls) {
    $result = [];

    foreach ($ids as $i => $id) {
      if ($urls[$i]) { // ignore empty records
        $item = $id
          ? self::get_by_id($id)
          : Model::factory(self::class)->create();
        $item->url = $urls[$i];
        $result[] = $item;
      }
    }

    return $result;
  }

}
