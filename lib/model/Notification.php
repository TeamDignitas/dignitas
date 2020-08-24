<?php

class Notification extends Precursor {
  use ObjectTypeIdTrait;

  const PAGE_SIZE = 50;

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
   * Returns a localized name of the notification's type, to be displayed in
   * the notification log.
   */
  function getTypeName() {
    switch ($this->type) {
      case Subscription::TYPE_CHANGES:
        return _('subscription-changes');
      case Subscription::TYPE_VOTE:
        return _('subscription-vote');
      case Subscription::TYPE_NEW_ANSWER:
        return _('subscription-new-answer');
      case Subscription::TYPE_NEW_COMMENT:
        return _('subscription-new-comment');
    }
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

  /**
   * Loads notifications for the current user.
   *
   * @param int $page 1-based page to load
   */
  static function getPage(int $page) {
    return Model::factory('Notification')
      ->where('userId', User::getActiveId())
      ->order_by_desc('createDate')
      ->offset(($page - 1) * self::PAGE_SIZE)
      ->limit(self::PAGE_SIZE)
      ->find_many();
  }

  /**
   * Returns the number of notification pages for the current user.
   */
  static function getNumPages() {
    $n = Notification::count_by_userId(User::getActiveId());
    return ceil($n / self::PAGE_SIZE);
  }
}
