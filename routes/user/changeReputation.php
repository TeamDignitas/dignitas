<?php

/**
 * For illegal operations, we return an error code of 404 and print the
 * JSON-encoded error message.
 **/

const MAX_REPUTATION = 1000000;

$value = Request::get('value');

$user = User::getActive();

if (!$user) {
  $error = _('Please log in to perform this action.');
} else if (!Config::DEVELOPMENT_MODE) {
  $error = _('Changing your reputation is only allowed in development mode.');
} else if ($value < 1 || $value > MAX_REPUTATION) {
  $error = sprintf(_('Reputation must be an integer between 1 and %d'), MAX_REPUTATION);
} else {
  $error = null;
  $user->reputation = $value;
  $user->save();
}

header('Content-Type: application/json');
if ($error) {
  http_response_code(404);
  print json_encode($error);
} else {
  print json_encode(Str::formatNumber($value));
}
