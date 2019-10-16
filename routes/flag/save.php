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

// ensure the user has flagging privilege and has remaining flags
if (!User::may(User::PRIV_FLAG)) {
  $error = sprintf(_('You need at least %s reputation to flag.'),
                   Str::formatNumber(User::PRIV_FLAG));
}

if (!$error && User::getRemainingFlags() <= 0) {
  $fpd = User::getFlagsPerDay();
  $error = sprintf(ngettext('You can use at most one flag every 24 hours.',
                            'You can use at most %d flags every 24 hours.',
                            $fpd), $fpd);
}

$flag = Flag::create($userId, $objectType, $objectId, $reason, $duplicateId, $details);

// ensure the object exists and is not flagged
if (!$error && !$flag->getObject()) {
  $error = _('Cannot flag: object does not exist.');
}

if (!$error && $flag->getObject()->isFlagged()) {
  $error = _('You already have a pending flag for this object.');
}

if (!$error) {
  $flag->save();
}

header('Content-Type: application/json');
if ($error) {
  http_response_code(404);
  print json_encode($error);
} else {
  print json_encode(_('Your flag was saved.'));
}
