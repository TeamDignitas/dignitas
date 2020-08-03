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

  /**
   * Creates Notifications for all users who subscribed to changes to $obj of
   * type $type. When a $delegate is passed, stores that object in the
   * Notification instead of $obj.
   *
   * Rationale: When issuing notifications of a new answer to a statement, we
   * should link to the answer, not to the statement. Linking to the statement
   * would be useless when there are 20 answers already.
   */
  static function notify($obj, $type, $delegate = null) {
    $objType = $obj->getObjectType();
    $target = $delegate ?? $obj;
    $targetType = $target->getObjectType();

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
        $userId, $targetType, $target->id, $type, false);

      if (!$existing) {
        $not = self::create($userId, $target, $type);
      }
    }
  }
}
