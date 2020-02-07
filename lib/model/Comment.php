<?php

class Comment extends Proto {
  // Comments support Markdown, but do not support attachments explicitly.
  // Therefore, using MarkdownTrait is not necessary.
  use FlaggableTrait, ObjectTypeIdTrait, VotableTrait;

  const MAX_LENGTH = 500;

  function getObjectType() {
    return self::TYPE_COMMENT;
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
   * Returns the object's comments.
   */
  static function getFor($object) {
    return Model::factory('Comment')
      ->table_alias('c')
      ->select('c.*')
      ->join('comment_ext', [ 'c.id', '=', 'ce.commentId' ], 'ce')
      ->where('c.objectType', $object->getObjectType())
      ->where('c.objectId', $object->id)
      ->where('c.status', Ct::STATUS_ACTIVE)
      ->order_by_desc('ce.score')
      ->order_by_asc('c.createDate')
      ->find_many();
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
      return sprintf(_('Comments can be at most %d characters long.'), self::MAX_LENGTH);
    }

    return null;
  }

  /**
   * Checks whether the active user may delete this comment.
   *
   * @return boolean
   */
  function isDeletable() {
    return
      User::isModerator() ||
      $this->userId == User::getActiveId();
  }

  function close($reason) {
    throw new Exception('Comments should never be closed.');
  }

  function delete() {
    throw new Exception('Comments should never be deleted at the DB level.');
  }
}
