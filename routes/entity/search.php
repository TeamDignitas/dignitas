<?php

$term = Request::get('term');
$exceptId = Request::get('exceptId', 0);

if ($term) {
  $entities = Search::searchEntities($term, $exceptId);
} else {
  $entities = [];
}

$resp = ['results' => []];
foreach ($entities as $e) {
  $resp['results'][] = [
    'id' => $e->id,
    'text' => $e->name,
  ];
}

header('Content-Type: application/json');
print json_encode($resp);
