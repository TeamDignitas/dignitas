<?php

User::enforceModerator();

$id = Request::get('id');

$cr = CannedResponse::get_by_id($id);
if (!$cr) {
  FlashMessage::add(_('info-no-such-canned-response'));
  Util::redirectToHome();
}

Smart::assign([
  'history' => ObjectDiff::loadFor($cr),
  'title' => _('info-canned-response-history'),
]);

Smart::addResources('history');
Smart::display('bits/history.tpl');
