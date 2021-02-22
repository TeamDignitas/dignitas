<?php

Util::assertNotLoggedIn();

$token = Request::get('token');
$password = Request::get('password');
$password2 = Request::get('password2');
$submitButton = Request::has('submitButton');

$pt = PasswordToken::get_by_token($token);

// Validate the token and load the user
$user = null;
if (!$pt) {
  Snackbar::add(_('info-password-incorrect-token'));
} else if ($pt->createDate < time() - 24 * 3600) {
  Snackbar::add(_('info-password-expired-token'));
} else {
  $user = User::get_by_id($pt->userId);
  if (!$user) {
    Snackbar::add(_('info-password-incorrect-token'));
  }
}

if ($user && $submitButton) {

  $errors = validate($password, $password2);

  if ($errors) {
    Smart::assign(['errors' => $errors]);
  } else {
    $user->password = md5($password);
    $user->save();
    $pt->delete();
    Snackbar::add(_('info-password-changed'), 'success');
    Session::login($user);
  }

}

Smart::assign([
  'token' => $token,
  'user' => $user,
  'password' => $password,
  'password2' => $password2,
]);
Smart::display('auth/resetPassword.tpl');

/*************************************************************************/

function validate($password, $password2) {
  $errors = [];

  $msg = User::validateNewPassword($password, $password2);
  if ($msg) {
    $errors['password'][] = $msg;
  }

  return $errors;
}
