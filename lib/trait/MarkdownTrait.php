<?php

// Method implementations for objects that have Markdown fields. For now, we
// care about extracting references to uploaded attachments from those
// Markdown fields.

trait MarkdownTrait {

  // Array of fields that can contain Markdown (and hence attachment references).
  abstract function getMarkdownFields();

  /**
   * What type of ObjectType should AttachmentReferences use?
   *
   * @return int One of the ObjectTypes::TYPE_* values.
   */
  abstract function getObjectType();

  // keep in sync with UploadTrait.php::$URL_PATTERN
  private static $URL_PCRE = '#(?:href|src)="%s/([0-9]+)/[a-z0-9]+\.[a-z]+"#';

  private function extractAttachmentReferences() {
    $seenIds = []; // prevent duplicates
    $type = $this->getObjectType();

    AttachmentReference::delete_all_by_objectType_objectId($type, $this->id);
    $pattern = sprintf(self::$URL_PCRE, Router::link('attachment/view'));

    foreach ($this->getMarkdownFields() as $fieldName) {
      preg_match_all($pattern, $this->$fieldName, $matches, PREG_SET_ORDER);

      foreach ($matches as $m) {
        $attachmentId = $m[1];

        if (!isset($seenIds[$attachmentId])) {
          AttachmentReference::insert($type, $this->id, $attachmentId);
          $seenIds[$attachmentId] = true;
        }
      }
    }

  }

  function save() {
    parent::save();
    $this->extractAttachmentReferences();
  }
}
