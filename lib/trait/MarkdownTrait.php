<?php

/**
 * Method implementations for objects that have Markdown fields. For now, we
 * care about
 * (1) extracting references to uploaded attachments from Markdown fields;
 * (2) displaying the source code of Markdown fields.
 */

trait MarkdownTrait {

  // Array of fields that can contain Markdown (and hence attachment references).
  abstract function getMarkdownFields();

  // keep in sync with UploadTrait.php::$URL_PATTERN
  private static $URL_PCRE = '#(?:href|src)="/?%s/([0-9]+)/[a-z0-9]+\.[a-z]+"#';

  private function extractAttachmentReferences() {
    $seenIds = []; // prevent duplicates

    AttachmentReference::deleteObject($this);
    $pattern = sprintf(self::$URL_PCRE, Router::link('attachment/view', false, false));

    foreach ($this->getMarkdownFields() as $fieldName) {
      preg_match_all($pattern, $this->$fieldName, $matches, PREG_SET_ORDER);

      foreach ($matches as $m) {
        $attachmentId = $m[1];

        if (!isset($seenIds[$attachmentId])) {
          AttachmentReference::insert($this, $attachmentId);
          $seenIds[$attachmentId] = true;
        }
      }
    }

  }

  function save($modUserId = null) {
    parent::save($modUserId);
    $this->extractAttachmentReferences();
  }
}
