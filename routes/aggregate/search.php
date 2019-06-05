<?php

$q = Request::get('q');

$objects = Search::run($q);

Smart::assign([
  'entities' => $objects['entities'],
  'tags' => $objects['tags'],
]);
Smart::display('aggregate/search.tpl');
