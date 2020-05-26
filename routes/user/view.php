<?php

$id = Request::get('id');
$nickname = Request::get('nickname');

$user = User::get_by_id_nickname($id, $nickname);

if (!$user) {
  FlashMessage::add(_('info-no-such-user'));
  Util::redirectToHome();
}

$statements = Statement::count_by_userId($user->id);
$answers = Answer::count_by_userId($user->id);

Smart::assign([
  'user' => $user,
  'answers' => $answers,
  'statements' => $statements,
  'actions' => $user->getActions(),
]);
Smart::addResources('imageModal');
Smart::display('user/view.tpl');
