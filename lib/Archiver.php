<?php

/**
 * Defines the archiving protocol. Provides a dummy implementation that simply
 * logs the jobs it is given.
 **/
class Archiver {

  protected bool $dryRun;
  protected array $options;

  function __construct($options) {
    $this->options = $options;
    $this->dryRun = $options['dryRun'] ?? true;
  }

  function add($url) {
    if ($this->dryRun) {
      Log::info('DRY RUN archiving: [%s]', $url);
    } else {
      Log::info('archiving: [%s]', $url);
    }
  }

  /**
   * Invokes the archiver on the given links. Duplicate URLs are OK; the
   * archiver should notice them and skip them.
   *
   * @param array<ArchivedLink> $archivedLinks
   */
  function batchAdd($archivedLinks) {
    foreach ($archivedLinks as $al) {
      $this->add($al->url);
    }
  }

}
