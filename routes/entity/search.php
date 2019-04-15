<?php

$term = Request::get('term');

if ($term) {
  $entities = Model::factory('Entity')
    ->where_like('name', "%{$term}%")
    ->order_by_asc('name')
    ->limit(10)
    ->find_many();
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
