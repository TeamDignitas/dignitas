<?php

if (!User::getActive()) {
  Util::redirectToLogin(); // just ensure user is logged in
}

$saveButton = Request::has('saveButton');

$user = User::getActive();

if ($saveButton) {
  $user->nickname = Request::get('nickname');
  $user->email = Request::get('email');
  $user->aboutMe = Request::get('aboutMe');
  $password = Request::get('password');
  $password2 = Request::get('password2');

  $deleteImage = Request::has('deleteImage');
  $fileData = Request::getFile('image', 'User');

  $errors = validate($user, $password, $password2, $fileData);
  if (empty($errors)) {
    if ($password) {
      $user->password = md5($password);
    }
    $user->saveWithFile($fileData, $deleteImage);
    Action::create(Action::TYPE_UPDATE, $user);

    FlashMessage::add(_('info-changes-saved'), 'success');
    Util::redirect(Router::userLink($user));
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

Smart::addResources('easymde');
Smart::assign([
  'user' => $user,
]);
Smart::display('user/edit.tpl');

/*************************************************************************/

function validate($user, $password, $password2, $fileData) {
  $errors = [];

  $msg = User::canChooseNickname($user->nickname);
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

  // image field
  $fileError = UploadTrait::validateFileData($fileData);
  if ($fileError) {
    $errors['image'][] = $fileError;
  }

  return $errors;
}
