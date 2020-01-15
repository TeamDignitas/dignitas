<?php

class EntityLink extends BaseObject {
  use UrlTrait;

  function __toString() {
    return $this->url;
  }
}
