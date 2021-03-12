<?php

/**
 * Defines the archiving protocol. Provides a dummy implementation that simply
 * logs the jobs it is given.
 *
 * Sample options:
 *
 * const ARCHIVER_OPTIONS = []; // This archiver ignores all options.
 **/
class Archiver {

  protected array $options;

  function __construct(array $options) {
    $this->options = $options;
  }

  /**
   * Invokes the archiver on the given links. This one simply logs them.
   *
   * @param array<ArchivedLink> $archivedLinks
   */
  function add(array $archivedLinks) {
    foreach ($archivedLinks as $al) {
      Log::info('adding %s', $al->url);
    }
  }

  /**
   * Tells the archiver to remove the given links. This one simply logs them.
   *
   * @param array<ArchivedLink> $archivedLinks
   */
  function remove(array $archivedLinks) {
    foreach ($archivedLinks as $al) {
      Log::info('removing %s', $al->url);
    }
  }

}
