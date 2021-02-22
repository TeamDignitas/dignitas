<?php

$id = Request::get('id');
$nickname = Request::get('nickname');

$user = User::get_by_id_nickname($id, $nickname);

if (!$user) {
  Snackbar::add(_('info-no-such-user'));
  Util::redirectToHome();
}

$statements = Statement::count_by_userId($user->id);
$answers = Answer::count_by_userId($user->id);

// moderators and the user herself can see the user's bans
$bans = (User::isModerator() || ($user->id == User::getActiveId()))
  ? $user->getActiveBans()
  : [];

Smart::assign([
  'user' => $user,
  'answers' => $answers,
  'statements' => $statements,
  'actions' => $user->getActionPage(1),
  'actionPages' => $user->getNumActionPages(),
  'bans' => $bans,
]);
Smart::addResources('imageModal', 'pagination');
Smart::display('user/view.tpl');
