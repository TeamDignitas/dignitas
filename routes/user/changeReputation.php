<?php

/**
 * For illegal operations, we return an error code of 404 and print the
 * JSON-encoded error message.
 **/

const MAX_REPUTATION = 1000000;

$value = Request::get('value');

$user = User::getActive();

if (!$user) {
  $error = _('info-must-log-in');
} else if (!Config::DEVELOPMENT_MODE) {
  $error = _('info-change-reputation-devel');
} else if ($value < 1 || $value > MAX_REPUTATION) {
  $error = sprintf(_('info-reputation-range-%d'), MAX_REPUTATION);
} else {
  $error = null;
  $user->setReputation($value);
}

header('Content-Type: application/json');
if ($error) {
  http_response_code(404);
  print json_encode($error);
} else {
  print json_encode(Str::formatNumber($value));
}
