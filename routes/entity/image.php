<?php

$id = Request::get('id');
$fileName = Request::get('fileName');

$entity = Entity::get_by_id($id);

if (!$entity) {
  http_response_code(404);
} else {
  $entity->renderFile($fileName);
}
