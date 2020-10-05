<?php

class Notification extends Precursor {
  use ObjectTypeIdTrait;

  const TYPE_CHANGES = 0x1;
  const TYPE_VOTE = 0x2;
  const TYPE_NEW_ANSWER = 0x4;
  const TYPE_NEW_COMMENT = 0x8;
  const TYPE_MENTION = 0x10;
  const TYPE_ALL = 0x1f;

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
      case self::TYPE_CHANGES:
        return _('notification-changes');
      case self::TYPE_VOTE:
        return _('notification-vote');
      case self::TYPE_NEW_ANSWER:
        return _('notification-new-answer');
      case self::TYPE_NEW_COMMENT:
        return _('notification-new-comment');
      case self::TYPE_MENTION:
        return _('notification-mention');
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
        self::create($userId, $target, $type);
      }
    }
  }

  /**
   * Creates Notifications for users @mentioned in $obj->$field. To avoid
   * notifying a user every time an article is edited, only notify users who
   * are not mentioned in the previous version of $obj->$field.
   * @param string $obj An article (statement, answer, comment).
   * @param string $field The field being scanned for mentions.
   */
  static function notifyMentions($obj, $field) {
    $cur = $obj->$field;
    $prev = $obj->getPreviousValue($field, '');

    $regexp = sprintf('/@(%s)/u', User::NICKNAME_REGEXP);
    preg_match_all($regexp, $cur, $matches);
    $curUsers = $matches[1];
    preg_match_all($regexp, $prev, $prevMatches);
    $prevUsers = $prevMatches[1];

    foreach ($curUsers as $nickname) {
      if (!in_array($nickname, $prevUsers)) {
        $u = User::get_by_nickname($nickname);
        if ($u) {
          self::create($u->id, $obj, self::TYPE_MENTION);
        }
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

  /**
   * Marks all notifications for the current user as seen.
   */
  static function markAllSeen() {
    $nots = Notification::get_all_by_userId_seen(User::getActiveId(), false);
    foreach ($nots as $not) {
      $not->seen = true;
      $not->save();
    }
  }
}
