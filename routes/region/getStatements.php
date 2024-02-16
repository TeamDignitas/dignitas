<?php

/**
 * Display page #p (1-based) of the region's statements as JSON.
 *
 * For illegal operations, we return an error code of 404 and print the
 * JSON-encoded error message.
 **/

$id = Request::get('id');
$p = Request::get('page');

header('Content-Type: application/json');

try {

  $r = Region::get_by_id($id);
  if (!$r) {
    throw new Exception(_('info-no-such-region'));
  }

  $query = $r->getStatementQuery();
  $statements = Statement::getPage($query, $p, Statement::REGION_PAGE_SIZE);
  Smart::assign('statements', $statements);

  $html = Smart::fetch('bits/statementList.tpl');
  $response = [
    'html' => $html,
  ];
  print json_encode($response);

} catch (Exception $e) {

  http_response_code(404);
  print json_encode($e->getMessage());

}
