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

$priv = 0;
if ($type == Vote::TYPE_STATEMENT && $value == 1) {
  $priv = User::PRIV_UPVOTE_STATEMENT;
} else if ($type == Vote::TYPE_STATEMENT && $value == -1) {
  $priv = User::PRIV_DOWNVOTE_STATEMENT;
} else if ($type == Vote::TYPE_ANSWER && $value == 1) {
  $priv = User::PRIV_UPVOTE_ANSWER;
} else if ($type == Vote::TYPE_ANSWER && $value == -1) {
  $priv = User::PRIV_DOWNVOTE_ANSWER;
} else if ($type == Vote::TYPE_COMMENT && $value == 1) {
  $priv = User::PRIV_UPVOTE_COMMENT;
} else if ($type == Vote::TYPE_COMMENT && $value == -1) {
  $priv = User::PRIV_DOWNVOTE_COMMENT;
} else {
  $error = _('info-bad-vote');
}

if ($priv && !User::may($priv)) {
  $error = sprintf(_('info-minimum-reputation-vote-%s'),
                   Str::formatNumber($priv));
}

if (!$error && Ban::exists(Ban::TYPE_VOTE)) {
  $error = _('info-banned-vote');
}

$vote = Vote::loadOrCreate($userId, $type, $objectId);

// ensure the object exists
if (!$error && !$vote->getObject()) {
  $error = _('info-vote-no-object');
}

// prevent self votes
if (!$error && ($vote->getObjectUserId() == $userId)) {
  $error = _('info-cannot-self-vote');
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
