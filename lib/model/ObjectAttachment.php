<?php

class ObjectAttachment extends BaseObject implements DatedObject {

  static function insert($objectClass, $objectId, $attachmentId) {
    $oa = Model::factory('ObjectAttachment')->create();
    $oa->objectClass = $objectClass;
    $oa->objectId = $objectId;
    $oa->attachmentId = $attachmentId;
    $oa->save();
  }

}
