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
  function wrapArchiveBoxCommand(string $cmd) {
    return sprintf(
      "sudo su %s -c 'cd \"%s\" && archivebox %s 2>/dev/null'",
      $this->user,
      $this->workingDir,
      $cmd
    );
  }

  /**
   * Adds slashes. Also compensates for a shortcoming of ArchiveBox concerning
   * parentheses: https://github.com/ArchiveBox/ArchiveBox/issues/235
   * @return string The corrected URL.
   */
  function formatUrl(string $url) {
    $url = str_replace([ '(', ')' ],
                       [ '%28', '%29' ],
                       $url);
    $url = addslashes($url);
    return $url;
  }

  /**
   * Checkes whether ArchiveBox has this URL.
   *
   * @return bool
   */
  function exists($url) {
    $cmd = sprintf('list --json "%s"', $this->formatUrl($url));
    $cmd = $this->wrapArchiveBoxCommand($cmd);
    Log::debug('Running %s', $cmd);
    OS::execute($cmd, $json);
    $result = json_decode($json);

    // TODO discern between sucessful and unsuccessful crawls
    return !empty($result);
  }

  /**
   * Invokes the archiver on the given links. Relies on ArchiveBox to skip
   * duplicate URLs.
   *
   * @param array<ArchivedLink> $archivedLinks
   */
  function add(array $archivedLinks) {
    foreach ($archivedLinks as $i => $al) {
      $cmd = sprintf('add "%s"', $this->formatUrl($al->url));
      $cmd = $this->wrapArchiveBoxCommand($cmd);
      Log::info('(%d/%d) Running %s', $i + 1, count($archivedLinks), $cmd);

      if (!$this->dryRun) {
        exec($cmd);
        if ($this->exists($al->url)) {
          $al->status = ArchivedLink::STATUS_ARCHIVED;
          $al->save();
        }
      }
    }
  }

  /**
   * Tells the archiver to remove the given links.
   *
   * @param array<ArchivedLink> $archivedLinks
   */
  function remove(array $archivedLinks) {
    foreach ($archivedLinks as $i => $al) {
      $cmd = sprintf('remove --delete --yes "%s"', $this->formatUrl($al->url));
      $cmd = $this->wrapArchiveBoxCommand($cmd);
      Log::info('(%d/%d) Running %s', $i + 1, count($archivedLinks), $cmd);

      if (!$this->dryRun) {
        exec($cmd);
        $al->delete();
      }
    }
  }

}
