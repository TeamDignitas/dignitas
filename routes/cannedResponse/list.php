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

  FlashMessage::add(_('info-canned-response-order-updated'), 'success');
  Util::redirectToRoute('cannedResponse/list');
}

Smart::assign('cannedResponses', CannedResponse::loadAll());
Smart::addResources('sortable');
Smart::display('cannedResponse/list.tpl');
