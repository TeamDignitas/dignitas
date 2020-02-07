<?php

Util::assertNotLoggedIn();

if (!Config::ALLOW_REGISTRATION) {
  FlashMessage::add(_('Registration is disabled.'));
  Util::redirectToHome();
}

$nickname = Request::get('nickname');
$email = Request::get('email');
$password = Request::get('password');
$password2 = Request::get('password2');
$remember = Request::has('remember');
$submitButton = Request::has('submitButton');

if ($submitButton) {
  $errors = validate($nickname, $email, $password, $password2);

  if ($errors) {
    Smart::assign(['errors' => $errors]);
  } else {

    $user = Model::factory('User')->create();
    $user->nickname = $nickname;
    $user->email = $email;
    $user->password = md5($password);
    $user->save();
    $user->setReputation(1);
    Session::login($user, $remember);
  }
}

Smart::assign([
  'nickname' => $nickname,
  'email' => $email,
  'password' => $password,
  'password2' => $password2,
  'remember' => $remember,
]);
Smart::display('auth/register.tpl');

/*************************************************************************/

function validate($nickname, $email, $password, $password2) {
  $errors = [];

  $msg = User::validateNickname($nickname);
  if ($msg) {
    $errors['nickname'][] = $msg;
  }

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
