<?php

class AttachmentReference extends BaseObject implements DatedObject {
  use ObjectTypeIdTrait;

  static function insert($objectType, $objectId, $attachmentId) {
    $ar = Model::factory('AttachmentReference')->create();
    $ar->objectType = $objectType;
    $ar->objectId = $objectId;
    $ar->attachmentId = $attachmentId;
    $ar->save();
  }

}
