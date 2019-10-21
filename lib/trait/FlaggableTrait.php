<?php

/**
 * Method implementations for objects that can be flagged.
 */
trait FlaggableTrait {

  /**
   * Returns the flag type that this object uses.
   *
   * @return int Returns one of the Flag::TYPE_* constants.
   */
  abstract function getFlagType();

  /**
   * Checks if the object is flagged by the active user.
   *
   * @return object Returns the flag object or false if there is no flag.
   **/
  function isFlagged() {
    return Flag::get_by_userId_objectType_objectId(
      User::getActiveId(), $this->getFlagType(), $this->id
    );
  }

  /**
   * Checks if the current user may flag the object.
   *
   * @return bool
   **/
  function isFlaggable() {
    return User::canFlag($this->getFlagType(), $this->id);
  }

}
