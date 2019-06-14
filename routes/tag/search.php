<?php

$term = Request::get('term');

if ($term) {
  $tags = Search::searchTags($term);
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
