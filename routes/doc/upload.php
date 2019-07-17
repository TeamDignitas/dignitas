<?php

$imageData = Request::getImage('file');
$imgError = Img::validateImageStatus($imageData['status']);
if ($imgError) {
  // handle bad upload
}

$fileName = sprintf('%s.%s', Str::randomString(Doc::NAME_LENGTH), $imageData['extension']);
$subdir = substr($fileName, 0, 2);
$fullPath = sprintf('%supload/%s/%s', Config::SHARED_DRIVE, $subdir, $fileName);
@mkdir(dirname($fullPath), 0777, true);
copy($imageData['tmpImageName'], $fullPath);

$url = Router::link('doc/view') . '/' . $fileName;

$output = [ 'filename' => $url ];

header('Content-Type: application/json');
print json_encode($output);
