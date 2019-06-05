<?php

$q = Request::get('q');

$objects = Search::run($q);

Smart::assign([
  'entities' => $objects['entities'],
  'tags' => $objects['tags'],
  'query' => $q,
]);
Smart::display('aggregate/search.tpl');
