<?php

class Subscription extends Precursor {

  use ObjectTypeIdTrait;

  /**
   * The $object's ID may be null/zero, in which case the subscription applies
   * to the given change on all objects of $object's type.
   */
  static function subscribe($object, $userId = null, $typeMask = Notification::TYPE_ALL) {
    if (!$userId) {
      $userId = User::getActiveId();
    }

    $sub = Subscription::get_by_userId_objectType_objectId(
      $userId, $object->getObjectType(), (int)$object->id);

    if ($sub && !$sub->active) {
      return; // user explicitly unsubscribed from this object
    }

    if ($sub) {
      $sub->typeMask |= $typeMask;
    } else {
      $sub = Model::factory('Subscription')->create();
      $sub->userId = $userId;
      $sub->objectType = $object->getObjectType();
      $sub->objectId = (int)$object->id;
      $sub->typeMask = $typeMask;
      $sub->createDate = time();
    }

    $sub->save();
  }

  /**
   * Ensures that the current user is a moderator, then subscribes her to
   * notifications about new users being created.
   */
  static function subscribeNewUser() {
    if (User::isModerator()) {
      $stub = Model::factory('User')->create();
      // clean up any inactive subscriptions
      self::unsubscribeNewUser();
      self::subscribe($stub, null, Notification::TYPE_NEW_USER);
    }
  }

  /**
   * Unsubscribes the current user from notifications about new users being
   * created.
   */
  static function unsubscribeNewUser() {
    Subscription::delete_all_by_userId_objectType_objectId(
      User::getActiveId(),
      Proto::TYPE_USER,
      0);
  }

  /**
   * Returns true iff the current user is a moderator and is subscribed to
   * notifications about new users being created.
   */
  static function isSubscribedNewUser() {
    $s = Subscription::get_by_userId_objectType_objectId_active(
      User::getActiveId(),
      Proto::TYPE_USER,
      0,
      true);
    return User::isModerator() && ($s != null);
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
