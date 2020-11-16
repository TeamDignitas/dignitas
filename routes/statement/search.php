<?php

$page = Request::get('page', 0);
$order = Request::get('order', 0);

$filters = [
  'entityId' => Request::get('entityId', 0),
  'exceptId' => Request::get('exceptId', 0),
  'maxDate' =>  Time::extendPartialDate(Request::get('maxDate')),
  'minDate' =>  Request::get('minDate'),
  'term' =>  addslashes(Request::get('term')),
  'verdicts' => Request::getArray('verdicts'),
];

list($numPages, $statements) = Search::searchStatements($filters, $order, $page);

Smart::assign('statements', $statements);
$htmlList = Smart::fetch('bits/statementList.tpl');

$resp = [
  'numPages' => $numPages,
  'html' => $htmlList,
  'results' => [],
];
foreach ($statements as $s) {
  $resp['results'][] = [
    'id' => $s->id,
    'text' => $s->summary,
  ];
}

header('Content-Type: application/json');
print json_encode($resp);


/*************************************************************************/
