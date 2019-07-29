<?php

class AttachmentReference extends BaseObject implements DatedObject {

  static function insert($objectClass, $objectId, $attachmentId) {
    $ar = Model::factory('AttachmentReference')->create();
    $ar->objectClass = $objectClass;
    $ar->objectId = $objectId;
    $ar->attachmentId = $attachmentId;
    $ar->save();
  }

}
