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

}
