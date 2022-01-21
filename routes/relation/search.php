<?php

$order = Request::get('order', 0);

$filters = [
  'term' =>  addslashes(Request::get('term')),
  'active' => Request::has('active'),
];

$results = Search::searchRelations($filters, $order);

Smart::assign('results', $results);
$html = Smart::fetch('bits/relationSearchResults.tpl');

$resp = [
  'numPages' => 1,
  'html' => $html,
  'results' => [],
];

header('Content-Type: application/json');
print json_encode($resp);
