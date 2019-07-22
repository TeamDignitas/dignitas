<?php

$output = [];

$imageData = Request::getFile(
  'file', Config::MAX_ATTACHMENT_SIZE, Config::ATTACHMENT_MIME_TYPES);
$imgError = Img::validateImageData($imageData);

if (!User::may(User::PRIV_UPLOAD_ATTACHMENT)) {

  $output['error'] = sprintf(
    _('You need at least %s reputation to upload documents.'),
    Str::formatNumber(User::PRIV_UPLOAD_ATTACHMENT));

} else if ($imgError) {

  $output['error'] = $imgError;

} else {

  $a = Model::factory('Attachment')->create();
  $a->extension = $imageData['extension'];
  $a->userId = User::getActiveId();
  $a->save();

  $fullPath = $a->getFullPath();
  @mkdir(dirname($fullPath), 0777, true);
  copy($imageData['tmpImageName'], $fullPath);

  $output['filename'] = $a->getUrl();
  Log::info('uploaded attachment %s to %s', $a->id, $fullPath);

}

header('Content-Type: application/json');
if (isset($output['error'])) {
  http_response_code(400); // bad request
}
print json_encode($output);
