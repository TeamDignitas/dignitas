<?php

class Subscription extends Precursor {

  use ObjectTypeIdTrait;

  const TYPE_CHANGES = 0x1;
  const TYPE_VOTE = 0x2;
  const TYPE_NEW_ANSWER = 0x4;
  const TYPE_NEW_COMMENT = 0x8;
  const TYPE_ALL = 0xf;

  static function subscribe($object, $userId = null, $typeMask = self::TYPE_ALL) {
    if (!$userId) {
      $userId = User::getActiveId();
    }

    $sub = Subscription::get_by_userId_objectType_objectId(
      $userId, $object->getObjectType(), $object->id);

    if ($sub && !$sub->active) {
      return; // user explicitly unsubscribed from this object
    }

    if ($sub) {
      $sub->typeMask |= $typeMask;
    } else {
      $sub = Model::factory('Subscription')->create();
      $sub->userId = $userId;
      $sub->objectType = $object->getObjectType();
      $sub->objectId = $object->id;
      $sub->typeMask = $typeMask;
      $sub->createDate = time();
    }

    $sub->save();
  }

  static function exists($object) {
    $userId = User::getActiveId();
    if (!$userId) {
      return false;
    }

    return Subscription::get_by_userId_objectType_objectId_active(
      $userId,
      $object->getObjectType(),
      $object->id,
      true
    );
  }

}
