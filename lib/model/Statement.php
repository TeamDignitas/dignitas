<?php

class Statement extends BaseObject implements DatedObject {

  function getEntity() {
    return Entity::get_by_id($this->entityId);
  }

}
