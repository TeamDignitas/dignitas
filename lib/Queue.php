<?php

/**
 * Class that handles adding/removing items to/from review queues
 */
class Queue {

  const TYPE_UNHELPFUL = 0;
  const TYPE_DUPLICATE = 1;
  const TYPE_OTHER = 2;

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
