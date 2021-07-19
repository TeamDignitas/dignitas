<?php

if (!User::getActive()) {
  Util::redirectToLogin();
}

$userId = User::getActiveId();
$statementDrafts = Statement::get_all_by_userId_status($userId, Ct::STATUS_DRAFT);
$answerDrafts = Answer::get_all_by_userId_status($userId, Ct::STATUS_DRAFT);

Smart::assign([
  'answerDrafts' => $answerDrafts,
  'statementDrafts' => $statementDrafts,
]);

Smart::display('aggregate/drafts.tpl');
