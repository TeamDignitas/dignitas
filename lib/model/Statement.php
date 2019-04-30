<?php

class Statement extends BaseObject implements DatedObject {

  function getEntity() {
    return Entity::get_by_id($this->entityId);
  }

  function getUser() {
    return User::get_by_id($this->userId);
  }

  function isEditable() {
    return
      User::may(User::PRIV_EDIT_STATEMENT) ||  // can edit any statements
      $this->userId = User::getActiveId();     // can always edit user's own statements
  }

}
