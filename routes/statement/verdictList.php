<?php

/**
 * Discards all errors silently.
 */

$statementType = Request::get('statementType');

// If set, also includes some HTML suitable for selectpicker-style options.
$selectpicker = Request::has('selectpicker');

$verdicts = Statement::VERDICTS_BY_TYPE[$statementType]
  ?? [ Statement::VERDICT_NONE ];

$data = [];
foreach ($verdicts as $v) {
  $rec = [
    'value' => $v,
    'text' => Statement::verdictName($v),
  ];

  if ($selectpicker) {
    Smart::assign('v', $v);
    $rec['html'] = Smart::fetch('bits/selectPickerVerdict.tpl');
  }

  $data[] = $rec;
}

header('Content-Type: application/json');
print json_encode($data);
