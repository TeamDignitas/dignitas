<?php

User::enforceModerator();

if (!Config::ALLOW_INVITES) {
  Snackbar::add(_('info-invites-disabled'));
  Util::redirect(Router::link('invite/list'));
}

$saveButton = Request::has('saveButton');
$i = Model::factory('Invite')->create();

if ($saveButton) {
  $i->email = Request::get('email');

  $errors = validate($i);
  if (empty($errors)) {
    $i->senderId = User::getActiveId();
    $i->code = Str::randomString(30);
    $i->save();

    sendEmail($i);
    
    Snackbar::add(_('info-invite-sent'), 'success');
    Util::redirect(Router::link('invite/list'));
  } else {
    Smart::assign('errors', $errors);
  }
}

Smart::assign([
  'i' => $i,
]);
Smart::display('invite/add.tpl');

/*************************************************************************/

function validate($i) {
  $errors = [];

  if (!$i->email) {
    $errors['email'][] = _('info-must-enter-email');
  } else if (!filter_var($i->email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'][] = _('info-incorrect-email');
  } else if (User::get_by_email($i->email)) {
    $errors['email'][] = _('info-invite-user-exists');
  }

  if (Invite::get_by_email($i->email)) {
    $errors['email'][] = _('info-invite-email-exists');
  }

  return $errors;
}

function sendEmail($i) {
  Log::notice('Sending out invite to %s', $i->email);

  Smart::assign([
    'sender' => User::getActive(),
    'code' => $i->code,
  ]);
  $from = Config::CONTACT_EMAIL;
  $subject = _('email-subject-invite');
  $body = Smart::fetch('email/invite.tpl');

  Mailer::setRealMode();
  Mailer::send($from, [$i->email], $subject, $body);
}

