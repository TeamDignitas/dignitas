<?php

/**
 * Display page #p (1-based) of the entity's statements as JSON.
 *
 * For illegal operations, we return an error code of 404 and print the
 * JSON-encoded error message.
 **/

$id = Request::get('id');
$mentions = Request::get('mentions'); // 1 for mentions, 0 for statements
$p = Request::get('page');

header('Content-Type: application/json');

try {

  $e = Entity::get_by_id($id);
  if (!$e) {
    throw new Exception(_('info-no-such-entity'));
  }

  if ($mentions) {
    $query = $e->getInvolvementQuery();
  } else {
    $query = $e->getStatementQuery();
  }

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
