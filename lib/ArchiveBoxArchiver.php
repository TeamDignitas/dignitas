<?php
/**
 * Archiver that uses a local installation of ArchiveBox. Must be run by a
 * user that can su to another user.
 *
 * Sample options:
 *
 * const ARCHIVER_OPTIONS = [
 *   // mandatory; user to run as (owner of the archive)
 *   'user' => 'archivebox',
 *   // mandatory; absolute path of the archive on the disk
 *   'workingDir' => '/srv/http/dignitas-archive',
 *   // optional; report what would happen without touching anything
 *   // (this can also be achieved by passing -n to archive.php
 *   'dryRun' => true,
 * ];
 *
 **/
class ArchiveBoxArchiver extends Archiver {

  private bool $dryRun;
  private string $user;
  private string $workingDir;

  function __construct(array $options) {
    parent::__construct($options);
    $this->dryRun = $options['dryRun'] ?? false;
    $this->user = $options['user'] ?? 'root';
    $this->workingDir = $options['workingDir'] ?? '/tmp';
  }

  /**
   * Wraps an ArchiveBox command in the environment described by the options.
   *
   * @return string
   */
  function wrapArchiveBoxCommand(string $cmd) {
    $env = 'PYTHONPATH=/opt/archivebox/lib/python3.13/site-packages/';
    return sprintf(
      "sudo %s su %s -c 'cd \"%s\" && archivebox %s 2>/dev/null'",
      $env,
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
   * Gets storage information about this URL.
   *
   * @return mixed The timestamp and path if ArchiveBox has this URL, false
   * otherwise.
   */
  function getData($url) {
    $cmd = sprintf('list --json "%s"', $this->formatUrl($url));
    $cmd = $this->wrapArchiveBoxCommand($cmd);
    Log::debug('Running %s', $cmd);
    OS::execute($cmd, $json);
    $result = json_decode($json);

    $timestamp = $result[0]->timestamp ?? null;
    if (!$timestamp) {
      return false;
    }

    $wgetHistory = $result[0]->history->wget ?? null;
    if (empty($wgetHistory)) {
      return false;
    }

    $lastWget = $wgetHistory[array_key_first($wgetHistory)];
    $status = $lastWget->status ?? null;
    if ($lastWget->status != 'succeeded') {
      return false;
    }

    return [
      'timestamp' => $timestamp,
      'path' => $lastWget->output,
    ];
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

        // update our record if the crawl was successful
        $data = $this->getData($al->url);
        if ($data) {
          $al->status = ArchivedLink::STATUS_ARCHIVED;
          $al->timestamp = $data['timestamp'];
          $al->path = $data['path'];
        } else {
          // Mark it as failed so we don't keep retrying. Sysadmins should run
          // 'archivebox update' periodically to retry failed jobs.
          $al->status = ArchivedLink::STATUS_FAILED;
        }
        $al->save();
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
