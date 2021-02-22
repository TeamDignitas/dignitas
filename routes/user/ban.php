<?php

User::enforceModerator();

$id = Request::get('id');
$deleteId = Request::get('deleteId');
$saveButton = Request::has('saveButton');

$user = User::get_by_id($id);

if ($deleteId) {
  $ban = Ban::get_by_id($deleteId);
  $user = User::get_by_id($ban->userId);
  $ban->delete();

  Snackbar::add(_('info-ban-deleted'));
  Util::redirect(Router::userLink($user));
}

if ($saveButton) {
  $banDurations = Request::getArray('banDuration'); // durations are in minutes
  $now = time();

  foreach ($banDurations as $type => $duration) {
    if ($duration == Ban::EXPIRATION_NEVER) {
      Ban::extend($user->id, $type, Ban::EXPIRATION_NEVER);
    } else if ($duration) {
      $duration *= 60; // convert to seconds
      Ban::extend($user->id, $type, $now + $duration);
    }
  }

  Snackbar::add(_('info-changes-saved'));
  Util::redirect(Router::userLink($user));
}

Smart::assign([
  'user' => $user,
]);
Smart::display('user/ban.tpl');
