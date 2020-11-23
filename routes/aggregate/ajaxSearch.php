<?php

$q = Request::get('q');

$objects = Search::run($q);

$results = [];

foreach ($objects['entities'] as $e) {
  Smart::assign('entity', $e);
  Smart::assign('aliases', $e->getAliases());
  $results[] = [
    'id' => $e->id,
    'url' => Router::link('entity/view') . '/' . $e->id,
    'html' => Smart::fetch('bits/ajaxSearchResultEntity.tpl'),
  ];
}

foreach ($objects['statements'] as $s) {
  Smart::assign('statement', $s);
  Smart::assign('entity', $s->getEntity());
  $results[] = [
    'id' => $s->id,
    'url' => Router::link('statement/view') . '/' . $s->id,
    'html' => Smart::fetch('bits/ajaxSearchResultStatement.tpl'),
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

foreach ($objects['regions'] as $r) {
  Smart::assign('region', $r);
  $results[] = [
    'id' => $r->id,
    'url' => Router::link('region/view') . '/' . $r->id,
    'html' => Smart::fetch('bits/ajaxSearchResultRegion.tpl'),
  ];
}

$output['results'] = $results;

header('Content-Type: application/json');
print json_encode($output);
