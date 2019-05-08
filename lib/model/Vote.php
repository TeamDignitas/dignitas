<?php

class Vote extends BaseObject implements DatedObject {

  const TYPE_STATEMENT = 1;
  const TYPE_ANSWER = 2;

  private $object = false; // not to be confused with null

  static function loadOrCreate($userId, $type, $objectId) {
    $vote = self::get_by_userId_type_objectId($userId, $type, $objectId);
    if (!$vote) {
      $vote = Model::factory('Vote')->create();
      $vote->userId = $userId;
      $vote->type = $type;
      $vote->objectId = $objectId;
    }
    return $vote;
  }

  function getObject() {
    if ($this->object === false) {
      switch ($this->type) {
        case Vote::TYPE_STATEMENT:
          $this->object = Statement::get_by_id($this->objectId);
          break;
        case Vote::TYPE_ANSWER:
          $this->object = Answer::get_by_id($this->objectId);
          break;
        default:
          $this->object = null; // prevents future attempts to look it up again
      }
    }
    return $this->object;
  }

  function getObjectScore() {
    return $this->object->score;
  }

  function saveValue($value) {
    // sanitize bad values to +1
    $value = ($value == -1) ? -1 : +1;

    if (!$this->id) {
      // new vote
      $this->value = $value;
      $this->save();

      $this->object->score += $this->value;
      $this->object->save();

    } else if ($this->value != $value) {
      // toggled vote
      $this->value = -$this->value;
      $this->save();

      $this->object->score += 2 * $this->value;
      $this->object->save();

    } else {
      // delete this vote (since button was clicked again)
      $this->object->score -= $this->value;
      $this->object->save();

      $this->delete();
    }
  }

}
