<?php

class Alias extends BaseObject {

  function __toString() {
    return $this->name;
  }

}
