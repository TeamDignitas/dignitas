<?php

$id = Request::get('id');
$fileName = Request::get('fileName');

$a = Attachment::get_by_id($id);

if (!$a) {
  http_response_code(404);
} else {
  $a->renderFile($fileName);
}
