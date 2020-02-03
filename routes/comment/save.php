<?php

/**
 * For illegal operations, we return an error code of 404 and print the
 * JSON-encoded error message.
 **/

$objectType = Request::get('objectType');
$objectId = Request::get('objectId');
$contents = Request::get('contents');

header('Content-Type: application/json');

try {

  $obj = BaseObject::getObjectByTypeId($objectType, $objectId);
  User::canComment($obj, true);

  $c = Comment::create($obj, $contents);
  $c->sanitize();

  $c->save();

  print json_encode(_('Your comment was saved.'));

} catch (Exception $e) {

  http_response_code(404);
  print json_encode($e->getMessage());

}
