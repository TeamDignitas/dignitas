<?php

User::enforce(1); /* just ensure user is logged in */

$saveButton = Request::has('saveButton');

$user = User::getActive();

if ($saveButton) {
  $user->nickname = Request::get('nickname');
  $user->email = Request::get('email');
  $user->aboutMe = Request::get('aboutMe');
  $password = Request::get('password');
  $password2 = Request::get('password2');

  $errors = validate($user, $password, $password2);
  if (empty($errors)) {
    if ($password) {
      $user->password = md5($password);
    }
    $user->save();

    FlashMessage::add(_('Changes saved.'), 'success');
    Util::redirectToSelf();
  } else {
    Smart::assign([
      'errors' => $errors,
      'password' => $password,
      'password2' => $password2,
    ]);
  }
} else {
  // first time loading the page
}

Smart::addResources('marked');
Smart::assign([
  'user' => $user,
]);
Smart::display('user/edit.tpl');

/*************************************************************************/

function validate($user, $password, $password2) {
  $errors = [];

  $msg = User::validateNickname($user->nickname);
  if ($msg) {
    $errors['nickname'][] = $msg;
  }

  $msg = User::canChooseEmail($user->email);
  if ($msg) {
    $errors['email'][] = $msg;
  }

  if ($password || $password2) {
    $msg = User::validateNewPassword($password, $password2);
    if ($msg) {
      $errors['password'][] = $msg;
    }
  }

  return $errors;
}
