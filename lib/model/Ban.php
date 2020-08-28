<?php

class Ban extends Proto {

  // ban types roughly follow user privileges
  const TYPE_ADD_ENTITY = 1;
  const TYPE_EDIT_ENTITY = 2;

  const TYPE_ADD_STATEMENT = 3;
  const TYPE_EDIT_STATEMENT = 4;

  const TYPE_ADD_ANSWER = 5;
  const TYPE_EDIT_ANSWER = 6;

  const TYPE_DELETE = 7;           // delete entities, statements, answers or comments
  const TYPE_VOTE = 8;
  const TYPE_FLAG = 9;
  const TYPE_COMMENT = 10;
  const TYPE_TAG = 11;             // add, edit or delete tags
  const TYPE_REVIEW = 12;          // access review queues

  const NUM_TYPES = 12;

  const EXPIRATION_NEVER = -1;

  static function typeName($type) {
    switch ($type) {
      case self::TYPE_ADD_ENTITY:      return _('ban-add-entity');
      case self::TYPE_EDIT_ENTITY:     return _('ban-edit-entity');
      case self::TYPE_ADD_STATEMENT:   return _('ban-add-statement');
      case self::TYPE_EDIT_STATEMENT:  return _('ban-edit-statement');
      case self::TYPE_ADD_ANSWER:      return _('ban-add-answer');
      case self::TYPE_EDIT_ANSWER:     return _('ban-edit-answer');
      case self::TYPE_DELETE:          return _('ban-delete');
      case self::TYPE_VOTE:            return _('ban-vote');
      case self::TYPE_FLAG:            return _('ban-flag');
      case self::TYPE_COMMENT:         return _('ban-comment');
      case self::TYPE_TAG:             return _('ban-tag');
      case self::TYPE_REVIEW:          return _('ban-review');
    }
  }

  function getTypeName() {
    return self::typeName($this->type);
  }

  function isPermanent() {
    return $this->expiration == self::EXPIRATION_NEVER;
  }

  /**
   * Updates a user's ban of a given type. If there is no ban for the given
   * type, creates one. Does nothing if a ban already exists with a later
   * expiration date.
   *
   * @param int $expiration A timestamp or Ban::EXPIRATION_NEVER.
   */
  static function extend($userId, $type, $expiration) {
    $ban = Ban::get_by_userId_type($userId, $type);
    if (!$ban) {
      $ban = Model::factory('Ban')->create();
      $ban->userId = $userId;
      $ban->type = $type;
    }
    if (!$ban->isPermanent() &&
        (($expiration > $ban->expiration) ||
         ($expiration == Ban::EXPIRATION_NEVER))) {
      $ban->expiration = $expiration;
      $ban->save();
    }
  }
}
