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

  /**
   * Logs an action which may have one of the following types:
   * - creating the $object;
   * - updating the $object;
   * - creating a pending edit for the $object;
   *
   * Only applicable to object types that support pending edits. Call *after*
   * cloning and/or saving the object.
   *
   * @param PendingEditTrait $object An object which may be a clone
   * @param int $originalId The original object's ID, which may be null
   */
  static function createUpdateAction($object, $originalId) {
    if (!$originalId) {
      self::create(self::TYPE_CREATE, $object);
    } else if ($object->id == $originalId) {
      self::create(self::TYPE_UPDATE, $object);
    } else {
      // $object is the clone; we want to log the action on the original
      $class = get_class($object);
      $orig = Model::factory($class)->where('id', $originalId)->find_one();
      self::create(self::TYPE_CREATE_PENDING_EDIT, $orig);
    }
  }

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
        $d = "[{$object->value}]";
        break;
      case Proto::TYPE_USER:
        $d = $object->nickname;
        break;
      default: $d = '';
    }
    $a->description = $d;

    $a->save();
  }

  /**
   * Logs a flagging/unflagging/review action.
   */
  static function createFlagReviewAction($flag) {
    $review = $flag->getReview();
    $obj = $review->getObject();
    $weight = $flag->getWeight();
    $moderator = $weight == Flag::WEIGHT_MODERATOR;

    if ($flag->vote == Flag::VOTE_KEEP) {
      if ($review->reason == Ct::REASON_PENDING_EDIT) {
        $type = Action::TYPE_VOTE_ACCEPT_PENDING_EDIT;
      } else if ($review->reason == Ct::REASON_REOPEN) {
        $type = $moderator ? Action::TYPE_REOPEN : Action::TYPE_VOTE_REOPEN;
      } else {
        $type = Action::TYPE_VOTE_KEEP;
      }
    } else {
      if ($review->reason == Ct::REASON_PENDING_EDIT) {
        $type = Action::TYPE_VOTE_REFUSE_PENDING_EDIT;
      } else if ($review->reason == Ct::REASON_REOPEN) {
        $type = Action::TYPE_VOTE_IGNORE_REOPEN;
      } else if (!$moderator) {
        $type = Action::TYPE_VOTE_REMOVE;
      } else {
        $action = Review::REMOVE_ACTION_MAP[$review->objectType][$review->reason] ?? null;
        $type = ($action == Review::ACTION_CLOSE)
          ? Action::TYPE_CLOSE
          : Action::TYPE_DELETE;
      }
    }

    self::create($type, $obj);
  }

  /**
   * Returns a localized name of the action's type, to be displayed in the
   * action log.
   */
  function getTypeName() {
    switch ($this->type) {
      case self::TYPE_CREATE:
        return _('action-created');
      case self::TYPE_UPDATE:
        return _('action-updated');
      case self::TYPE_CLOSE:
        return _('action-closed');
      case self::TYPE_DELETE:
        return _('action-deleted');
      case self::TYPE_REOPEN:
        return _('action-reopened');
      case self::TYPE_CREATE_PENDING_EDIT:
        return _('action-created-pending-edit');
      case self::TYPE_VOTE_UP:
        return _('action-voted-up');
      case self::TYPE_VOTE_DOWN:
        return _('action-voted-down');
      case self::TYPE_RETRACT_VOTE:
        return _('action-retracted-vote');
      case self::TYPE_VOTE_KEEP:
        return _('action-voted-keep');
      case self::TYPE_VOTE_ACCEPT_PENDING_EDIT:
        return _('action-voted-accept-pending-edit');
      case self::TYPE_VOTE_REOPEN:
        return _('action-voted-reopen');
      case self::TYPE_VOTE_REMOVE:
        return _('action-voted-remove');
      case self::TYPE_VOTE_REFUSE_PENDING_EDIT:
        return _('action-voted-refuse-pending-edit');
      case self::TYPE_VOTE_IGNORE_REOPEN:
        return _('action-voted-ignore-reopen');
      case self::TYPE_RETRACT_FLAG:
        return _('action-retracted-flag');
    }
  }

  function getObject() {
    return Proto::getObjectByTypeId($this->objectType, $this->objectId);
  }
}
