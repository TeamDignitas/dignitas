<?php

/**
 * For illegal operations, we return an error code of 404 and print the
 * JSON-encoded error message.
 **/

const MAX_REPUTATION = 1000000000;

$reputation = Request::get('reputation');
$moderator = Request::get('moderator');

$user = User::getActive();

if (!$user) {
  $error = _('info-must-log-in');
} else if (!Config::DEVELOPMENT_MODE) {
  $error = _('info-change-reputation-devel');
} else if ($reputation < -MAX_REPUTATION || $reputation > MAX_REPUTATION) {
  $error = sprintf(_('info-reputation-range-%d-%d'), -MAX_REPUTATION, MAX_REPUTATION);
} else {
  Log::notice(
    'setting reputation to %d and moderator to %d for %s',
    $reputation, $moderator, $user
  );
  $error = null;
  $user->moderator = $moderator;
  $user->save();
  $user->setReputation($reputation);
}

header('Content-Type: application/json');
if ($error) {
  http_response_code(404);
  print json_encode($error);
} else {
  print json_encode('');
}
