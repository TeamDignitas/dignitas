<?php

class Answer extends BaseObject implements DatedObject {

  function getUser() {
    return User::get_by_id($this->userId);
  }

  function sanitize() {
    $this->contents = trim($this->contents);
  }

}
