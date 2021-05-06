<?php

User::enforceModerator();

$id = Request::get('id');

$page = HelpPage::get_by_id($id);
if (!$page) {
  Snackbar::add(_('info-no-such-help-page'));
  Util::redirectToHome();
}

$title = _('info-help-page-history') . ': ' . $page->title;

Smart::assign([
  'history' => ObjectDiff::loadFor($page),
  'title' => $title,
  'backButtonText' => _('label-back-to-help-page'),
  'backButtonUrl' => $page->getViewUrl(),
]);

Smart::addResources('history');
Smart::display('bits/history.tpl');
