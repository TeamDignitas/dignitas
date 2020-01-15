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

$title = _('Statement history for') . ': ' . $statement->summary;

Smart::assign([
  'history' => ObjectDiff::getRevisions($statement),
  'title' => $title,
]);

Smart::addResources('history');
Smart::display('bits/history.tpl');
