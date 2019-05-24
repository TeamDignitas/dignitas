<?php

$term = Request::get('term');

if ($term) {
  $tags = Model::factory('Tag')
    ->where_like('value', "%{$term}%")
    ->order_by_asc('value')
    ->limit(20)
    ->find_many();
} else {
  $tags = [];
}

$resp = ['results' => []];
foreach ($tags as $t) {
  $resp['results'][] = [
    'id' => $t->id,
    'text' => $t->value,
  ];
}

header('Content-Type: application/json');
print json_encode($resp);
