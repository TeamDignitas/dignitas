<?php

$id = Request::get('id');
$fileName = Request::get('fileName');

@list($thumb, $extension) = explode('.', $fileName, 2);

$entity = Entity::get_by_id($id);

if (!$entity ||
    !$entity->imageExtension ||
    ($entity->imageExtension != $extension) ||
    !isset(Config::THUMB_SIZES[$thumb])) {
  http_response_code(404);
  exit;
}

// generate the thumb unless it already exists
$thumbLocation = $entity->getThumbLocation($thumb);
if (!file_exists($thumbLocation)) {
  list($width, $height) = Config::THUMB_SIZES[$thumb];
  @mkdir(dirname($thumbLocation), 0777, true);
  $cmd = sprintf('convert -geometry %dx%d -sharpen 1x1 %s %s',
                 $width, $height, $entity->getImageLocation(), $thumbLocation);
  OS::execute($cmd, $ignored);
}

// now dump it

if (file_exists($thumbLocation)) {

  $mimeType = array_search($extension, Config::IMAGE_MIME_TYPES);
  header('Content-Type:' . $mimeType);
  header('Content-Length: ' . filesize($thumbLocation));
  readfile($thumbLocation);

} else {
  http_response_code(404);
}

