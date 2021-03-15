<?php

$id = Request::get('id');

$al = ArchivedLink::get_by_id_status($id, ArchivedLink::STATUS_ARCHIVED);

if (!$al) {
  Snackbar::add(_('info-no-such-archived-link'));
  Util::redirectToHome();
}

Smart::assign([
  'archivedLink' => $al,
]);
Smart::display('archivedLink/view.tpl');
