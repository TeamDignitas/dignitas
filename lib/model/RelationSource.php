<?php

class RelationSource extends BaseObject implements DatedObject {

  function getDisplayName() {
    return parse_url($this->url, PHP_URL_HOST);
  }

}
