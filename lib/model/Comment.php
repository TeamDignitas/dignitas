<?php

class Comment extends Proto {
  // Comments support Markdown, but do not support attachments explicitly.
  // Therefore, using MarkdownTrait is not necessary.
  use FlaggableTrait, ObjectTypeIdTrait, VotableTrait;

  const MAX_LENGTH = 500;

  function getObjectType() {
    return self::TYPE_COMMENT;
  }

  function getViewUrl() {
    if ($this->objectType == Proto::TYPE_STATEMENT) {
      $statementId = $this->objectId;
    } else {
      $answer = $this->getObject();
      $statementId = $answer->statementId;
    }
    return sprintf('%s/%s#c%s', Router::link('statement/view'),
                   $statementId, $this->id);
  }

  function getUser() {
    return User::get_by_id($this->userId);
  }

  function getScore() {
    return CommentExt::getField($this->id, 'score');
  }

  function setScore($score) {
    return CommentExt::setField($this->id, 'score', $score);
  }

  /**
   * Returns a human-readable message if this Comment is deleted or null
   * otherwise.
   *
   * @return string
   */
  function getDeletedMessage() {
    if ($this->status != Ct::STATUS_DELETED) {
      return null;
    }

    $msg = _('info-this-comment-was-deleted');

    switch ($this->reason) {
      case Ct::REASON_SPAM: $r = _('info-comment-spam'); break;
      case Ct::REASON_ABUSE: $r = _('info-comment-abuse'); break;
      case Ct::REASON_BY_OWNER: $r = _('info-comment-by-owner'); break;
      case Ct::REASON_BY_USER: $r = _('info-by'); break;
      case Ct::REASON_OTHER: $r = _('info-other-reason'); break;
      case Ct::REASON_NOT_NEEDED: $r = _('info-comment-not-needed'); break;
      default: $r = '';
    }

    return $msg . ' ' . $r;
  }

  /**
   * Returns the object's comments, filtered by visibility to the current user.
   *
   * @param object $object A Statement or Answer.
   */
  static function getFor($object) {
    $comments = Model::factory('Comment')
      ->table_alias('c')
      ->select('c.*')
      ->left_outer_join('comment_ext', [ 'c.id', '=', 'ce.commentId' ], 'ce')
      ->where('c.objectType', $object->getObjectType())
      ->where('c.objectId', $object->id)
      ->order_by_desc('ce.score')
      ->order_by_asc('c.createDate')
      ->find_many();

    $results = [];
    foreach ($comments as $c) {
      if ($c->isViewable()) {
        $results[] = $c;
      }
    }
    return $results;
  }

  /**
   * Create a comment for the given object.
   *
   * @param Proto $object A statement or answer.
   * @return Comment
   */
  static function create($object, $contents) {
    $c = Model::factory('Comment')->create();
    $c->objectType = $object->getObjectType();
    $c->objectId = $object->id;
    $c->userId = User::getActiveId();
    $c->contents = $contents;
    return $c;
  }

  function sanitize() {
    $this->contents = trim($this->contents);
  }

  /**
   * @return string An error message or null on success.
   */
  function validate() {
    if (mb_strlen($this->contents) > self::MAX_LENGTH) {
      return sprintf(_('info-comment-length-limit-%d'), self::MAX_LENGTH);
    }

    return null;
  }

  /**
   * Checks whether the active user may view this comment.
   *
   * @return boolean
   */
  function isViewable() {
    return
      ($this->status == Ct::STATUS_ACTIVE) ||
      User::isModerator() ||
      ($this->userId == User::getActiveId());
  }

  /**
   * Subscribes the author to this comment and its statement/answer. Call
   * after saving the comment.
   */
  function subscribe() {
    if ($this->status != Ct::STATUS_PENDING_EDIT) {
      Subscription::subscribe($this);
    }
  }

  /**
   * Comments have no edits / pending edits, so this must be a new comment.
   * Trigger the parent's new comment notifications.
   */
  function notify() {
    $obj = $this->getObject();
    $obj->notify(Subscription::TYPE_NEW_COMMENT, $this);
  }

  /**
   * Checks whether the active user may delete this comment.
   *
   * @return boolean
   */
  function isDeletable() {
    return
      ($this->status == Ct::STATUS_ACTIVE) &&
      (User::isModerator() ||
       $this->userId == User::getActiveId());
  }

  function close($reason) {
    throw new Exception('Comments should never be closed.');
  }

  function delete() {
    throw new Exception('Comments should never be deleted at the DB level.');
  }
}
