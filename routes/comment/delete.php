<?php

$id = Request::get('id');

header('Content-Type: application/json');

try {

  $comment = Comment::get_by_id($id);

  if (!$comment) {
    throw new Exception(_('info-no-such-comment'));
  }

  if (!$comment->isDeletable()) {
    throw new Exception(_('info-cannot-delete-comment'));
  }

  $comment->markDeleted(Ct::REASON_BY_USER);

  print json_encode(_('info-comment-deleted'));

} catch (Exception $e) {

  http_response_code(404);
  print json_encode($e->getMessage());

}
