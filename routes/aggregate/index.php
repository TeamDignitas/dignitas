<?php

$statements = Model::factory('Statement')
  ->order_by_desc('createDate')
  ->limit(10)
  ->find_many();

$entities = Model::factory('Entity')
  ->order_by_desc('createDate')
  ->limit(10)
  ->find_many();

Smart::assign([
  'pageType' => 'home',
  'statements' => $statements,
  'entities' => $entities,
]);
Smart::display('aggregate/index.tpl');
