<?php

Util::assertLoggedIn();

$entityId = Request::get('entityId');
$contents = Request::get('contents');
$saveButton = Request::has('saveButton');

Smart::assign([
]);
Smart::display('statement/edit.tpl');

/*************************************************************************/

function validate($email, $password, &$errors) {
  $errors = [];

  if (!$email) {
    $errors['email'][] = _('Please enter an email address.');
  }

  if (!$password) {
    $errors['password'][] = _('Please enter a password.');
  }

  $user = null;
  if ($email && $password) {
    $user = User::get_by_email_password($email, md5($password));
    if (!$user) {
      $errors['password'][] = _('Incorrect email address or password.');
    }
  }

  return $user;
}
