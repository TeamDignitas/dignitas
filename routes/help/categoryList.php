<?php

$saveButton = Request::has('saveButton');

User::enforceModerator();

if ($saveButton) {
  $ids = Request::getArray('categoryIds');
  $rank = 0;
  foreach ($ids as $id) {
    $cat = HelpCategory::get_by_id($id);
    $cat->rank = ++$rank;
    $cat->save();
  }

  Snackbar::add(_('info-order-saved'), 'success');
  Util::redirectToRoute('help/index');
}

Smart::assign('categories', HelpCategory::loadAll());
Smart::addResources('sortable');
Smart::display('help/categoryList.tpl');
