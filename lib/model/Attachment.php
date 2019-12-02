<?php

class Attachment extends BaseObject {
  use UploadTrait;

  private function getFileSubdirectory() {
    return 'attachment';
  }

  private function getFileRoute() {
    return 'attachment/view';
  }

  function delete() {
    Log::warning("Deleted attachment {$this->id} ({$this->name}.{$this->fileExtension})");
    $this->deleteFiles();
    AttachmentReference::delete_all_by_attachmentId($this->id);
    parent::delete();
  }

}
