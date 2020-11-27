<?php

$page = Request::get('page', 0);
$order = Request::get('order', 0);

$filters = [
  'exceptId' => Request::get('exceptId', 0),
  'regionId' => Request::get('regionId', 0),
  'term' =>  addslashes(Request::get('term')),
];

list($numPages, $entities) = Search::searchEntities($filters, $order, $page);

Smart::assign('entities', $entities);
$htmlList = Smart::fetch('bits/entityList.tpl');

$resp = [
  'numPages' => $numPages,
  'html' => $htmlList,
  'results' => [],
];
foreach ($entities as $e) {
  $resp['results'][] = [
    'id' => $e->id,
    'text' => $e->name,
  ];
}

header('Content-Type: application/json');
print json_encode($resp);
