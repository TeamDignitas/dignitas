<?php

/**
 * For illegal operations, we return an error code of 404 and print the
 * JSON-encoded error message.
 **/

$value = Request::get('value');
$type = Request::get('type');
$objectId = Request::get('objectId');

$userId = User::getActiveId();
$error = null;

$priv = null;
if ($type == Vote::TYPE_STATEMENT && $value == 1) {
  $priv = User::PRIV_UPVOTE_STATEMENT;
} else if ($type == Vote::TYPE_STATEMENT && $value == -1) {
  $priv = User::PRIV_DOWNVOTE_STATEMENT;
} else if ($type == Vote::TYPE_ANSWER && $value == 1) {
  $priv = User::PRIV_UPVOTE_ANSWER;
} else if ($type == Vote::TYPE_ANSWER && $value == -1) {
  $priv = User::PRIV_DOWNVOTE_ANSWER;
} else {
  $error = _('Bad vote format.');
}

if ($priv && !User::may($priv)) {
  $error = _('You are not allowed to vote in this matter.');
}

$vote = Vote::loadOrCreate($userId, $type, $objectId);

// ensure the object exists
if (!$error && !$vote->getObject()) {
  $error = _('Cannot vote: object does not exist.');
}

if (!$error) {
  $vote->saveValue($value);
}

header('Content-Type: application/json');
if ($error) {
  http_response_code(404);
  print json_encode($error);
} else {
  print json_encode($vote->getObjectScore());
}
