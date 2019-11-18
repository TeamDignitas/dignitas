<?php

// load recent viewable statements
$statements = Model::factory('Statement');

if (!User::may(User::PRIV_DELETE_STATEMENT)) {
  $statements = $statements->where_not_equal('status', Ct::STATUS_DELETED);
}

$statements = $statements
  ->order_by_desc('createDate')
  ->limit(10)
  ->find_many();

// load recent entities
$entities = Model::factory('Entity')
  ->where_not_equal('status', Ct::STATUS_DELETED)
  ->order_by_desc('createDate')
  ->limit(10)
  ->find_many();

Smart::assign([
  'pageType' => 'home',
  'statements' => $statements,
  'entities' => $entities,
]);
Smart::display('aggregate/index.tpl');
