<?php

$q = Request::get('q');

$results = Search::run($q);

Smart::assign([
  'results' => $results,
  'query' => $q,
  'regions' => Region::loadAll(),
]);
Smart::addResources('pagination', 'datepicker', 'bootstrap-select');
Smart::display('aggregate/search.tpl');
