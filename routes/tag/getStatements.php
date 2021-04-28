<?php

/**
 * Display page #p (1-based) of the tag's statements as JSON.
 *
 * For illegal operations, we return an error code of 404 and print the
 * JSON-encoded error message.
 **/

$id = Request::get('id');
$p = Request::get('page');

header('Content-Type: application/json');

try {

  $t = Tag::get_by_id($id);
  if (!$t) {
    throw new Exception(_('info-no-such-tag'));
  }

  $query = $t->getStatementQuery();

  Smart::assign('statements', Statement::getPage($query, $p));

  $html = Smart::fetch('bits/statementList.tpl');
  $response = [
    'html' => $html,
  ];
  print json_encode($response);

} catch (Exception $e) {

  http_response_code(404);
  print json_encode($e->getMessage());

}
