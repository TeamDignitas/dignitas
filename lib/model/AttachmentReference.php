<?php

class AttachmentReference extends BaseObject {
  use ObjectTypeIdTrait;

  static function insert($object, $attachmentId) {
    $ar = Model::factory('AttachmentReference')->create();
    $ar->objectType = $object->getObjectType();
    $ar->objectId = $object->id;
    $ar->attachmentId = $attachmentId;
    $ar->save();
  }

}
