<?php

/**
 * Method implementations for objects that can be tagged.
 */
trait TaggableTrait {

  function getTags() {
    return User::isAnonymous()
      ? ObjectTag::getTagsVisibleToAnonymous($this)
      : ObjectTag::getTags($this);
  }

}
