<?php

User::enforceModerator();

$id = Request::get('id');

$cr = CannedResponse::get_by_id($id);
if (!$cr) {
  Snackbar::add(_('info-no-such-canned-response'));
  Util::redirectToHome();
}

Smart::assign([
  'history' => ObjectDiff::loadFor($cr),
  'title' => _('info-canned-response-history'),
  'backButtonText' => _('label-back-to-canned-response'),
  'backButtonUrl' => Router::link('cannedResponse/list'),
]);

Smart::addResources('history');
Smart::display('bits/history.tpl');
