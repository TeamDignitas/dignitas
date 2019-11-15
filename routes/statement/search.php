<?php

$term = Request::get('term');
$exceptId = Request::get('exceptId', 0);

if ($term) {
  $term = addslashes($term);
  $statements = Search::searchStatements($term, $exceptId);
} else {
  $statements = [];
}

$resp = ['results' => []];
foreach ($statements as $s) {
  $resp['results'][] = [
    'id' => $s->id,
    'text' => $s->summary,
  ];
}

header('Content-Type: application/json');
print json_encode($resp);
