<?php

$id = Request::get('id');

$statement = Statement::get_by_id($id);
if (!$statement) {
  FlashMessage::add(_('info-no-such-statement'));
  Util::redirectToHome();
}

if (!$statement->isViewable()) {
  FlashMessage::add(_('info-restricted-statement'));
  Util::redirectToHome();
}

$title = _('title-statement-history') . ': ' . $statement->summary;

Smart::assign([
  'history' => ObjectDiff::loadFor($statement),
  'title' => $title,
]);

Smart::addResources('history');
Smart::display('bits/history.tpl');
