<?php

$output = [];

$fileData = Request::getFile('file', 'Attachment');
$fileError = UploadTrait::validateFileData($fileData);

if (!User::may(User::PRIV_UPLOAD_ATTACHMENT)) {

  $output['error'] = sprintf(
    _('info-minimum-reputation-upload-%s'),
    Str::formatNumber(User::PRIV_UPLOAD_ATTACHMENT));

} else if ($fileError) {

  $output['error'] = $fileError;

} else {

  $a = Model::factory('Attachment')->create();
  $a->userId = User::getActiveId();
  $a->saveWithFile($fileData, false);

  Smart::assign([
    'fullUrl' => $a->getFileUrl(UploadTrait::$FULL_GEOMETRY),
    'thumbUrl' => $a->getFileUrl(Config::THUMB_INLINE_ATTACHMENT),
  ]);
  $output['html'] = Smart::fetch('bits/inlineAttachment.tpl');

  Log::info('uploaded attachment %s to %s',
            $a->id,
            $a->getFileLocation(UploadTrait::$FULL_GEOMETRY));

}

header('Content-Type: application/json');
if (isset($output['error'])) {
  http_response_code(400); // bad request
}
print json_encode($output);
