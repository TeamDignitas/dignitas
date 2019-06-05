<?php

$q = Request::get('q');

$objects = Search::run($q);

$results = [];

foreach ($objects['entities'] as $e) {
  Smart::assign('entity', $e);
  $results[] = [
    'id' => $e->id,
    'url' => Router::link('entity/view') . '/' . $e->id,
    'html' => Smart::fetch('bits/ajaxSearchResultEntity.tpl'),
  ];
}

foreach ($objects['tags'] as $t) {
  Smart::assign('tag', $t);
  $results[] = [
    'id' => $t->id,
    'url' => Router::link('tag/view') . '/' . $t->id,
    'html' => Smart::fetch('bits/ajaxSearchResultTag.tpl'),
  ];
}

$output['results'] = $results;

header('Content-Type: application/json');
print json_encode($output);
