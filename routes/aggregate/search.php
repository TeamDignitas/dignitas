<?php

$q = Request::get('q');

$results = Search::run($q);

Smart::assign([
  'results' => $results,
  'query' => $q,
]);
Smart::addResources('pagination', 'datepicker');
Smart::display('aggregate/search.tpl');
