<?php

$id = Request::get('id');

$al = ArchivedLink::get_by_id($id);

if (!$al || $al->status != ArchivedLink::STATUS_ARCHIVED) {
  Snackbar::add(_('info-no-such-archived-link'));
  Util::redirectToHome();
}

Smart::assign([
  'archivedLink' => $al,
]);
Smart::display('archivedLink/view.tpl');
