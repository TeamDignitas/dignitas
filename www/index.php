<?php

require_once '../lib/Core.php';

Smart::assign([
  'message' => 'It works!!',
]);
Smart::display('index.tpl');
