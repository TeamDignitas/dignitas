<?php

$output = [];

$fileData = Request::getFile('file', 'Attachment');
$fileError = UploadTrait::validateFileData($fileData);

if (!User::may(User::PRIV_UPLOAD_ATTACHMENT)) {

  $output['error'] = sprintf(
    _('You need at least %s reputation to upload documents.'),
    Str::formatNumber(User::PRIV_UPLOAD_ATTACHMENT));

} else if ($fileError) {

  $output['error'] = $fileError;

} else {

  $a = Model::factory('Attachment')->create();
  $a->fileExtension = $fileData['extension'];
  $a->userId = User::getActiveId();
  $a->save();

  $fullPath = $a->getFullPath();
  @mkdir(dirname($fullPath), 0777, true);
  copy($fileData['tmpFileName'], $fullPath);

  $output['filename'] = $a->getUrl();
  Log::info('uploaded attachment %s to %s', $a->id, $fullPath);

}

header('Content-Type: application/json');
if (isset($output['error'])) {
  http_response_code(400); // bad request
}
print json_encode($output);
