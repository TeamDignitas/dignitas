<?php

$id = Request::get('id');

$statement = Statement::get_by_id($id);
if (!$statement) {
  FlashMessage::add(_('The statement you are looking for does not exist.'));
  Util::redirectToHome();
}

Smart::addResources('marked');
Smart::assign([
  'statement' => $statement,
  'entity' => $statement->getEntity(),
  'sources' => $statement->getSources(),
]);
Smart::display('statement/view.tpl');
