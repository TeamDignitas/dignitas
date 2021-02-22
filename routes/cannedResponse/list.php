<?php

$saveButton = Request::has('saveButton');

User::enforceModerator();

if ($saveButton) {
  $ids = Request::getArray('cannedResponseIds');
  $rank = 0;
  foreach ($ids as $id) {
    $cr = CannedResponse::get_by_id($id);
    $cr->rank = ++$rank;
    $cr->save();
  }

  Snackbar::add(_('info-order-saved'));
  Util::redirectToRoute('cannedResponse/list');
}

Smart::assign('cannedResponses', CannedResponse::loadAll());
Smart::addResources('sortable');
Smart::display('cannedResponse/list.tpl');
