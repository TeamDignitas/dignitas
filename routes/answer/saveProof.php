<?php

/**
 * For illegal operations, we return an error code of 404 and print the
 * JSON-encoded error message.
 **/

$answerId = Request::get('answerId');

header('Content-Type: application/json');

try {

  if (!User::isModerator()) {
    throw new Exception(_('info-proof-requires-moderator'));
  }

  $answer = Answer::get_by_id($answerId);
  if (!$answer) {
    throw new Exception(_('info-no-such-answer'));
  }

  if ($answer->hasPendingEdit()) {
    throw new Exception(_('info-proof-pending-edit'));
  }

  if ($answer->status != Ct::STATUS_ACTIVE) {
    throw new Exception(_('info-proof-active-status'));
  }

  $answer->proof = !$answer->proof;
  $answer->save();

  // must output something or the frontend won't see it as success
  print json_encode('');

} catch (Exception $e) {

  http_response_code(404);
  print json_encode($e->getMessage());

}
