<?php

/**
 * Class that handles adding/removing items to/from review queues
 */
class Queue {

  const TYPE_UNHELPFUL = 0;
  const TYPE_DUPLICATE = 1;
  const TYPE_OTHER = 2;

  const TYPES = [
    self::TYPE_UNHELPFUL, self::TYPE_DUPLICATE, self::TYPE_OTHER,
  ];

  const QUEUE_TO_REASON = [
    self::TYPE_UNHELPFUL => [
      Flag::REASON_SPAM,
      Flag::REASON_ABUSE,
      Flag::REASON_OFF_TOPIC,
      Flag::REASON_LOW_QUALITY,
    ],
    self::TYPE_DUPLICATE => [
      Flag::REASON_DUPLICATE,
    ],
    self::TYPE_OTHER => [
      Flag::REASON_OTHER,
    ],
  ];

  /**
   * Returns a localized description for a queue.
   *
   * @param int $type One of the Queue::TYPE_* values
   * @return string A localized description
   */
  static function getDescription($type) {
    switch ($type) {
      case self::TYPE_UNHELPFUL: return _('queue for items flagged as unhelpful');
      case self::TYPE_DUPLICATE: return _('queue for items flagged as duplicate');
      case self::TYPE_OTHER:     return _('queue for other items');
    }
  }

  /**
   * Returns a localized URL name for a queue.
   *
   * @param int $type One of the Queue::TYPE_* values
   * @return string A localized URL name
   */
  static function getUrlName($type) {
    switch ($type) {
      case self::TYPE_UNHELPFUL: return _('unhelpful');
      case self::TYPE_DUPLICATE: return _('duplicate');
      case self::TYPE_OTHER:     return _('other');
    }
  }

  /**
   * Returns a queue type given a localized URL name.
   *
   * @param string $urlName A localized URL name
   * @return int One of the Queue::TYPE_* values or null if nothing matches
   */
  static function getTypeFromUrlName($urlName) {
    // do this naively for now
    foreach (self::TYPES as $t) {
      if (self::getUrlName($t) == $urlName) {
        return $t;
      }
    }
    return null;
  }

  /**
   * Adds the object to the queue unless it is already in the queue.
   *
   * @param int $objectType value from Flag::TYPE_*
   * @param int $objectId object ID
   * @param int $queueType value from Queue::TYPE_*
   */
  static function ensure($objectType, $objectId, $queueType) {
    $exists = QueueItem::get_by_objectType_objectId_queueType(
      $objectType, $objectId, $queueType);

    if (!$exists) {
      $qi = Model::factory('QueueItem')->create();
      $qi->objectType = $objectType;
      $qi->objectId = $objectId;
      $qi->queueType = $queueType;
      $qi->save();
    }
  }

  /**
   * Checks if the object should be in every queue type.
   *
   * @param int $objectType value from Flag::TYPE_*
   * @param int $objectId object ID
   */
  static function check($objectType, $objectId) {
    // check if there are any pending flags
    foreach (self::QUEUE_TO_REASON as $queueType => $reasons) {
      $exists = Model::factory('Flag')
        ->where('objectType', $objectType)
        ->where('objectId', $objectId)
        ->where_in('reason', $reasons)
        ->where('status', Flag::STATUS_PENDING)
        ->find_one();

      if ($exists) {
        // if it's flagged, it should be in the queue
        self::ensure($objectType, $objectId, $queueType);
      } else {
        // if it's not flagged, it should not be in the queue
        QueueItem::delete_all_by_objectType_objectId_queueType(
          $objectType, $objectId, $queueType);
      }
    }
  }
}
