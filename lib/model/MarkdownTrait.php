<?php

// Method implementations for objects that have Markdown fields. For now, we
// care about extracting references to uploaded attachments from those
// Markdown fields.

trait MarkdownTrait {

  // Array of fields that can contain Markdown (and hence attachment references).
  abstract function getMarkdownFields();

  // keep in sync with UploadTrait.php::$URL_PATTERN
  private static $URL_PCRE = '#(?:href|src)="%s/([0-9]+)/[a-z0-9]+\.[a-z]+"#';

  function extractObjectAttachments() {
    $seenIds = []; // prevent duplicates

    $class = strtolower(get_class());

    ObjectAttachment::delete_all_by_objectClass_objectId($class, $this->id);
    $pattern = sprintf(self::$URL_PCRE, Router::link('attachment/view'));

    foreach ($this->getMarkdownFields() as $fieldName) {
      preg_match_all($pattern, $this->$fieldName, $matches, PREG_SET_ORDER);

      foreach ($matches as $m) {
        $attachmentId = $m[1];

        if (!isset($seenIds[$attachmentId])) {
          ObjectAttachment::insert($class, $this->id, $attachmentId);
          $seenIds[$attachmentId] = true;
        }
      }
    }

  }

  function save() {
    parent::save();
    $this->extractObjectAttachments();
  }
}
