<?php

/**
 * Stores one action performed by a user on an object. This serves to display
 * a chronological list of the user's actions.
 */
class Action extends Precursor {

  const TYPE_CREATE = 1;
  const TYPE_UPDATE = 2;
  const TYPE_CLOSE = 3;
  const TYPE_DELETE = 4;
  const TYPE_REOPEN = 5;
  const TYPE_CREATE_PENDING_EDIT = 6;
  const TYPE_VOTE_UP = 7;
  const TYPE_VOTE_DOWN = 8;
  const TYPE_RETRACT_VOTE = 9;
  const TYPE_VOTE_KEEP = 10;
  const TYPE_VOTE_ACCEPT_PENDING_EDIT = 11;
  const TYPE_VOTE_REOPEN = 12;
  const TYPE_VOTE_REMOVE = 13;
  const TYPE_VOTE_REFUSE_PENDING_EDIT = 14;
  const TYPE_VOTE_IGNORE_REOPEN = 15;
  const TYPE_RETRACT_FLAG = 16;

  static function create($type, $object) {
    $objectType = $object->getObjectType();

    $a = Model::factory('Action')->create();
    $a->userId = User::getActiveId();
    $a->createDate = time();
    $a->type = $type;
    $a->objectType = $objectType;
    $a->objectId = $object->id();

    // keep a description in case the object ever gets deleted
    switch ($objectType) {
      case Proto::TYPE_ANSWER:
      case Proto::TYPE_COMMENT:
        $d = Str::shorten($object->contents, 30);
        break;
      case Proto::TYPE_ENTITY:
        $d = $object->name;
        break;
      case Proto::TYPE_STATEMENT:
        $d = Str::shorten($object->summary, 200);
        break;
      case Proto::TYPE_TAG:
        $d = $object->value;
        break;
      case Proto::TYPE_USER:
        $d = $object->nickname;
        break;
      default: $d = '';
    }
    $a->description = $d;

    $a->save();
  }
}
