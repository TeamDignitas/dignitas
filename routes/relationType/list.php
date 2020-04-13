<?php

$saveButton = Request::has('saveButton');

User::enforceModerator();

if ($saveButton) {
  $ids = Request::getArray('ids');
  $rank = 0;
  foreach ($ids as $id) {
    $rt = RelationType::get_by_id($id);
    $rt->rank = ++$rank;
    $rt->save();
  }

  FlashMessage::add(_('info-order-saved'), 'success');
  Util::redirectToRoute('relationType/list');
}

Smart::assign([
  'relationTypes' => RelationType::loadAll(),
  'numEntityTypes' => count(EntityType::loadAll()),
]);
Smart::addResources('sortable');
Smart::display('relationType/list.tpl');
