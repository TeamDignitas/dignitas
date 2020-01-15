<?php

class StatementSource extends BaseObject {
  use UrlTrait;

  function __toString() {
    return $this->url;
  }

}
