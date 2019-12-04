<?php

// load recent viewable statements
$statements = Model::factory('Statement')
  ->where_not_equal('status', Ct::STATUS_PENDING_EDIT);

if (!User::may(User::PRIV_DELETE_STATEMENT)) {
  $statements = $statements->where_not_equal('status', Ct::STATUS_DELETED);
}

$statements = $statements
  ->order_by_desc('createDate')
  ->limit(10)
  ->find_many();

// load recent entities
$entities = Model::factory('Entity')
  ->where_not_in('status', [Ct::STATUS_DELETED, Ct::STATUS_PENDING_EDIT])
  ->order_by_desc('createDate')
  ->limit(10)
  ->find_many();

Smart::assign([
  'pageType' => 'home',
  'statements' => $statements,
  'entities' => $entities,
]);
Smart::display('aggregate/index.tpl');
