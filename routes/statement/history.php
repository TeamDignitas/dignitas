<?php

$id = Request::get('id');

$statement = Statement::get_by_id($id);
if (!$statement) {
  Snackbar::add(_('info-no-such-statement'));
  Util::redirectToHome();
}

if (!$statement->isViewable()) {
  Snackbar::add(_('info-restricted-statement'));
  Util::redirectToHome();
}

$title = _('title-statement-history') . ': ' . $statement->summary;

Smart::assign([
  'history' => ObjectDiff::loadFor($statement),
  'title' => $title,
  'backButtonText' => _('label-back-to-statement'),
  'backButtonUrl' => $statement->getViewUrl(),
]);

Smart::addResources('history');
Smart::display('bits/history.tpl');
