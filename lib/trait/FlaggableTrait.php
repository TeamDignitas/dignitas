<?php

/**
 * Method implementations for objects that can be flagged.
 */
trait FlaggableTrait {

  /**
   * Checks if the object is flagged by the active user.
   *
   * @return object Returns the flag object or false if there is no flag.
   */
  function isFlagged() {
    return Flag::get_by_userId_objectType_objectId(
      User::getActiveId(), $this->getObjectType(), $this->id
    );
  }

  /**
   * Checks if the current user may flag the object.
   *
   * @return bool
   */
  function isFlaggable() {
    return User::canFlag($this->getObjectType(), $this->id);
  }

  /**
   * Deletes all flags pertaining to this object and removes it from all queues.
   */
  function deleteFlagsAndQueueItems() {
    Flag::deleteObject($this);
    QueueItem::deleteObject($this);
  }
}
