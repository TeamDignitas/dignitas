<?php

Util::assertNotLoggedIn();

$email = Request::get('email');
$password = Request::get('password');
$password2 = Request::get('password2');
$remember = Request::has('remember');
$submitButton = Request::has('submitButton');

if ($submitButton) {
  $errors = validate($email, $password, $password2);

  if ($errors) {
    Smart::assign(['errors' => $errors]);
  } else {

    $user = Model::factory('User')->create();
    $user->email = $email;
    $user->password = md5($password);
    $user->save();
    Session::login($user, $remember);
  }
}

Smart::assign([
  'email' => $email,
  'password' => $password,
  'password2' => $password2,
  'remember' => $remember,
]);
Smart::display('auth/register.tpl');

/*************************************************************************/

function validate($email, $password, $password2) {
  $errors = [];

  $msg = User::canChooseEmail($email);
  if ($msg) {
    $errors['email'][] = $msg;
  }

  $msg = User::validateNewPassword($password, $password2);
  if ($msg) {
    $errors['password'][] = $msg;
  }

  return $errors;
}
