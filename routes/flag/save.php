<?php

/**
 * For illegal operations, we return an error code of 404 and print the
 * JSON-encoded error message.
 **/

$objectType = Request::get('objectType');
$objectId = Request::get('objectId');
$reason = Request::get('reason');
$duplicateId = Request::get('duplicateId');
$details = Request::get('details');

$userId = User::getActiveId();
$error = null;

if (!User::may(User::PRIV_FLAG)) {
  $error = sprintf(_('You need at least %s reputation to flag.'),
                   Str::formatNumber(User::PRIV_FLAG));
}

$flag = Flag::create($userId, $objectType, $objectId, $reason, $duplicateId, $details);

// ensure the object exists
if (!$error && !$flag->getObject()) {
  $error = _('Cannot flag: object does not exist.');
}

if (!$error) {
  $flag->save();
}

header('Content-Type: application/json');
if ($error) {
  http_response_code(404);
  print json_encode($error);
} else {
  print json_encode('');
}
