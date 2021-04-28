<?php

$id = Request::get('id');

$tag = Tag::get_by_id($id);

if (!$tag) {
  Snackbar::add(_('info-no-such-tag'));
  Util::redirectToHome();
}

$query = $tag->getStatementQuery();

Smart::assign([
  'tag' => $tag,
  'statements' => Statement::getPage($query, 1),
  'statementPages' => Statement::getNumPages($query),
]);
Smart::addResources('pagination');
Smart::display('tag/view.tpl');
