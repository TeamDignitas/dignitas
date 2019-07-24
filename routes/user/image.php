<?php

$id = Request::get('id');
$fileName = Request::get('fileName');

$user = User::get_by_id($id);

if (!$user) {
  http_response_code(404);
} else {
  $user->renderFile($fileName);
}
