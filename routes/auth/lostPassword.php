<?php

Util::assertNotLoggedIn();

$email = Request::get('email');
$submitButton = Request::has('submitButton');

Smart::assign(['email' => $email]);

if ($submitButton) {
  $errors = validate($email);

  if ($errors) {
    Smart::assign(['errors' => $errors]);
  } else {

    $user = User::get_by_email($email);
    if ($user) {
      Log::notice("Password reset requested for $email from " . $_SERVER['REMOTE_ADDR']);

      $pt = PasswordToken::create($user->id);

      // Send email
      Smart::assign([
        'homePage' => Config::URL_HOST . Config::URL_PREFIX,
        'token' => $pt->token,
      ]);
      $from = Config::CONTACT_EMAIL;
      $subject = _('email-subject-password-change');
      $body = Smart::fetch('email/lostPassword.tpl');

      Mailer::setRealMode();
      Mailer::send($from, [$email], $subject, $body);
    }

    // Display a confirmation even for incorrect addresses.
    Smart::display('auth/lostPasswordEmailSent.tpl');
    exit;
  }
}

Smart::display('auth/lostPassword.tpl');

/*************************************************************************/

function validate($email) {
  $errors = [];

  if (!$email) {
    $errors['email'] = _('info-must-enter-email');
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = _('info-incorrect-email');
  }

  return $errors;
}
