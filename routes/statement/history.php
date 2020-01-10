<?php

$id = Request::get('id');

$statement = Statement::get_by_id($id);
if (!$statement) {
  FlashMessage::add(_('The statement you are looking for does not exist.'));
  Util::redirectToHome();
}

if (!$statement->isViewable()) {
  FlashMessage::add(_('This statement was deleted and is only visible to privileged users.'));
  Util::redirectToHome();
}

Smart::assign([
  'history' => $statement->getDisplayHistory(),
  'statement' => $statement,
]);
Smart::addResources('history');
Smart::display('statement/history.tpl');
