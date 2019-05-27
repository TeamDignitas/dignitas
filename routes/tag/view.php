<?php

const STATEMENT_LIMIT = 10;

$id = Request::get('id');

$tag = Tag::get_by_id($id);

if (!$tag) {
  FlashMessage::add(_('The tag you are looking for does not exist.'));
  Util::redirectToHome();
}

$statementCount = Model::factory('ObjectTag')
  ->where('objectType', ObjectTag::TYPE_STATEMENT)
  ->where('tagId', $tag->id)
  ->count();
$statements = Model::factory('Statement')
  ->table_alias('s')
  ->select('s.*')
  ->join('object_tag', ['ot.objectId', '=', 's.id'], 'ot')
  ->where('ot.objectType', ObjectTag::TYPE_STATEMENT)
  ->where('ot.tagId', $tag->id)
  ->limit(STATEMENT_LIMIT)
  ->find_many();

Smart::assign([
  'tag' => $tag,
  'statements' => $statements,
  'statementCount' => $statementCount,
]);
Smart::display('tag/view.tpl');
