<?php

/**
 * Archiver that uses a local installation of ArchiveBox.
 **/
class ArchiveBoxArchiver extends Archiver {

  function add($url) {
    parent::add($url);
    if (!$this->dryRun) {
      Log::info('This is where we call archivebox add...');
    }
  }

}
