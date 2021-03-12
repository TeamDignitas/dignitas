<?php
/**
 * Archiver that uses a local installation of ArchiveBox. Must be run by a
 * user that can su to another user.
 *
 * Mandatory constructor options:
 *   user - user to run as (owner of the archive)
 *   workingDir - absolute path of the archive on the disk
 *
 * Optional constructor options:
 *   dryRun - report what would happen without touching anything
 **/
class ArchiveBoxArchiver extends Archiver {

  private bool $dryRun;
  private string $user;
  private string $workingDir;

  function __construct(array $options) {
    parent::__construct($options);
    $this->dryRun = $options['dryRun'] ?? true;
    $this->user = $options['user'] ?? 'root';
    $this->workingDir = $options['workingDir'] ?? '/tmp';
  }

  /**
   * Wraps an ArchiveBox command in the environment described by the options.
   *
   * @return string
   */
  function wrapArchiveBoxCommand($cmd) {
    return sprintf(
      "sudo su %s -c 'cd \"%s\" && archivebox %s'",
      $this->user,
      $this->workingDir,
      $cmd
    );
  }

  /**
   * Checkes whether ArchiveBox has this URL.
   *
   * @return bool
   */
  function exists($url) {
    $cmd = sprintf('list --json "%s"', addslashes($url));
    $cmd = $this->wrapArchiveBoxCommand($cmd);
    Log::info('Running %s', $cmd);
    exit;
  }

  /**
   * Invokes the archiver on the given links. Relies on ArchiveBox to skip
   * duplicate URLs.
   *
   * @param array<ArchivedLink> $archivedLinks
   */
  function add(array $archivedLinks) {
    foreach ($archivedLinks as $al) {
      $cmd = sprintf('add "%s"', addslashes($al->url));
      $cmd = $this->wrapArchiveBoxCommand($cmd);
      Log::info('Running %s', $cmd);

      if (!$this->dryRun) {
        exec($cmd);
      }
    }

  }

}
