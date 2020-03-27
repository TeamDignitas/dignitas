<?php

class Loyalty extends Precursor {

  static function create($fromEntityId, $toEntityId, $value) {
    $l = Model::factory('Loyalty')->create();
    $l->fromEntityId = $fromEntityId;
    $l->toEntityId = $toEntityId;
    $l->value = $value;
    return $l;
  }
}
