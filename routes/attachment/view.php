<?php

$fileName = Request::get('fileName');

@list($id, $extension) = explode('.', $fileName, 2);

$a = Attachment::get_by_id($id);

if (!$a || $a->extension != $extension) {
  http_response_code(404);
  exit;
}

$fullPath = $a->getFullPath();

if (!file_exists($fullPath)) {
  http_response_code(404);
  exit;
}

$mimeType = array_search($extension, Config::MIME_TYPES);
header('Content-Type:' . $mimeType);
header('Content-Length: ' . filesize($fullPath));
readfile($fullPath);
