<?php

class Notification extends Precursor {
  use ObjectTypeIdTrait;

  static function create(int $userId, Object $obj, int $type) {
    $not = Model::factory('Notification')->create();
    $not->userId = $userId;
    $not->objectType = $obj->getObjectType();
    $not->objectId = $obj->id;
    $not->type = $type;
    $not->createDate = time();
    $not->save();
    return $not;
  }

  static function notify($obj, $type) {
    $objType = $obj->getObjectType();

    // load active subscriptions
    $subs = Model::factory('Subscription')
      ->select('userId')
      ->distinct()
      ->where_not_equal('userId', User::getActiveId())
      ->where('objectType', $obj->getObjectType())
      ->where('objectId', $obj->id)
      ->where('active', true)
      ->where_raw("typeMask & {$type} != 0")
      ->find_array();

    foreach ($subs as $sub) {
      $userId = $sub['userId'];

      // cluster unseen notifications of the same type
      $existing = Notification::get_by_userId_objectType_objectId_type_seen(
        $userId, $objType, $obj->id, $type, false);

      if (!$existing) {
        $not = self::create($userId, $obj, $type);
      }
    }
  }
}
