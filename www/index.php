<?php

require_once '../lib/Core.php';

$user = Model::factory('User')->where('id', 1)->find_one();

Smart::assign([
  'pageType' => 'home',
]);
Smart::display('index.tpl');
