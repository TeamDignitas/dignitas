<?php

$id = Request::get('id');
$fileName = Request::get('fileName');

$domain = Domain::get_by_id($id);

if (!$domain) {
  http_response_code(404);
} else {
  $domain->renderFile($fileName);
}
