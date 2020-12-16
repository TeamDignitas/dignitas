<?php

/**
 * Discards all errors silently.
 */

$statementType = Request::get('statementType');

$verdicts = Statement::VERDICTS_BY_TYPE[$statementType]
  ?? [ Statement::VERDICT_NONE ];

$data = [];
foreach ($verdicts as $v) {
  $data[] = [
    'value' => $v,
    'text' => Statement::verdictName($v),
  ];
}

header('Content-Type: application/json');
print json_encode($data);
