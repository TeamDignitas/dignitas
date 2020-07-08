<?php

Util::assertNotLoggedIn();

$email = Request::get('email');
$password = Request::get('password');
$remember = Request::has('remember');
$submitButton = Request::has('submitButton');

$fakeEmail = Request::get('fakeEmail');
$fakeReputation = Request::get('fakeReputation') ?: 1;
$fakeModerator = Request::has('fakeModerator');
$referrer = Util::getReferrer();

if ($fakeEmail) {
  if (!Config::DEVELOPMENT_MODE) {
    FlashMessage::add(_('info-fake-login-devel'));
    Util::redirect('login');
  }
  $user = User::get_by_email($fakeEmail);
  if (!$user) {
    $user = Model::factory('User')->create();
    $user->setFakeId();
    $user->email = $fakeEmail;
    $user->nickname = explode('@', $fakeEmail)[0];
  }
  $user->moderator = $fakeModerator;
  $user->save();
  $user->setReputation($fakeReputation);
  Session::login($user, true, $referrer);
}

if ($submitButton) {
  $user = validate($email, $password, $errors);

  if ($user) {
    Session::login($user, $remember, $referrer);
  } else {
    Smart::assign(['errors' => $errors]);
  }
}

if (Config::DEVELOPMENT_MODE) {
  Smart::assign(['allowFakeLogins' => true]);
}

Smart::assign([
  'email' => $email,
  'remember' => $remember,
  'referrer' => $referrer,
]);
Smart::display('auth/login.tpl');

/*************************************************************************/

// returns a user upon successful credentials, null otherwise
function validate($email, $password, &$errors) {
  $errors = [];

  if (!$email) {
    $errors['email'][] = _('info-must-enter-email');
  }

  if (!$password) {
    $errors['password'][] = _('info-must-enter-password');
  }

  $user = null;
  if ($email && $password) {
    $user = User::get_by_email_password($email, md5($password));
    if (!$user) {
      $errors['password'][] = _('info-incorrect-login');
    }
  }

  return $user;
}
