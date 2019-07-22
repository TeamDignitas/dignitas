<?php

class Attachment extends BaseObject implements DatedObject {

  function getFullPath() {
    $subdir = $this->id / 1000;
    return sprintf('%supload/%d/%d.%s',
                   Config::SHARED_DRIVE, $subdir, $this->id, $this->extension);
  }

  function getUrl() {
    return sprintf('%s/%s.%s', Router::link('attachment/view'), $this->id, $this->extension);
  }

  function delete() {
    Log::warning("Deleted attachment {$this->id} ({$this->name}.{$this->extension})");
    ObjectAttachment::delete_all_by_attachmentId($this->id);
    parent::delete();
  }

}
