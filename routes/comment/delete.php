<?php

$id = Request::get('id');

header('Content-Type: application/json');

try {

  $comment = Comment::get_by_id($id);

  if (!$comment) {
    throw new Exception('That comment does not exist.');
  }

  if (!$comment->isDeletable()) {
    throw new Exception('You have insufficient privileges to delete this comment.');
  }

  $comment->status = Ct::STATUS_DELETED;
  $comment->save();

  print json_encode(_('Comment deleted.'));

} catch (Exception $e) {

  http_response_code(404);
  print json_encode($e->getMessage());

}
