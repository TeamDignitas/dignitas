<?php

class Vote extends Proto {
  use ObjectTypeIdTrait;

  static function loadOrCreate($userId, $objectType, $objectId) {
    $vote = self::get_by_userId_objectType_objectId($userId, $objectType, $objectId);
    if (!$vote) {
      $vote = Model::factory('Vote')->create();
      $vote->userId = $userId;
      $vote->objectType = $objectType;
      $vote->objectId = $objectId;
    }
    return $vote;
  }

  // encapsulate it here because we want to stress that every votable object
  // should have a user that created it
  function getObjectUserId() {
    $obj = $this->getObject();
    return $obj->userId ?? null;
  }

  function getObjectScore() {
    $obj = $this->getObject();
    return $obj->getScore();
  }

  function saveValue($value) {
    // sanitize bad values to +1
    $value = ($value == -1) ? -1 : +1;
    $obj = $this->getObject();

    if (!$this->id) {
      // new vote
      $this->value = $value;
      $this->save();

      $obj->changeScore($this->value);
      $obj->save();

    } else if ($this->value != $value) {
      // toggled vote
      $this->value = -$this->value;
      $this->save();

      $obj->changeScore(2 * $this->value);
      $obj->save();

    } else {
      // delete this vote (since button was clicked again)
      $obj->changeScore(-$this->value);
      $obj->save();

      $this->delete();
    }
  }

}
