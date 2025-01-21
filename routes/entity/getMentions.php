<?php

/**
 * Display page #p (1-based) of the entity's mentions as JSON. $mentionType is
 * meaningful for parties, where it can select mentions about the party or
 * about its members.
 *
 * For illegal operations, we return an error code of 404 and print the
 * JSON-encoded error message.
 **/

header('Content-Type: application/json');

$page = Request::get('page', 0);
$mentionType = Request::get('mentionType', Involvement::TYPE_OWN);
$id = Request::get('id');

try {

  $e = Entity::get_by_id($id);
  if (!$e) {
    throw new Exception(_('info-no-such-entity'));
  }

  if ($mentionType == Involvement::TYPE_OWN) {
    $query = $e->getInvolvementQuery();
  } else {
    $query = $e->getMemberInvolvementQuery();
  }

  $numPages = Statement::getNumPages($query);
  Smart::assign('statements', Statement::getPage($query, $page));

  $html = Smart::fetch('bits/statementList.tpl');
  $response = [
    'numPages' => $numPages,
    'html' => $html,
  ];

  print json_encode($response);

} catch (Exception $e) {

  http_response_code(404);
  print json_encode($e->getMessage());

}
