<?php
/**
 * This script goes through the upload folder on the shared drive and performs
 * various cleanup operations.
 *
 * - Remove files and folders that don't look like uploads or thumbnails.
 * - Remove attachment records that don't have matching files.
 *
 * Use with -n or --dry-run to see what the script would do without actually
 * executing anything.
 **/

require_once __DIR__ . '/../lib/Core.php';

const FILE_PATTERN =
  '#^' .
  Config::SHARED_DRIVE .
  'upload/(?<subdir>[a-z]+)(/(?<geom>[a-z0-9]+)(/(?<shard>[0-9]+)' .
  '(/(?<id>[0-9]+)\.(?<ext>[a-z0-9]+))?)?)?$#';

const FULL_SIZE_PATTERN =
  Config::SHARED_DRIVE .
  'upload/attachment/full/%d/*.*';

// What class to load for a given subdirectory. Inverse of UploadTrait::getFileSubdirectory().
const OBJECT_MAP = [
  'attachment' => 'Attachment',
  'entity' => 'Entity',
  'user' => 'User',
];

$opts = getopt('n', ['dry-run']);
$dryRun = isset($opts['n']) || isset($opts['dry-run']);

if ($dryRun) {
  print "---- DRY RUN ----\n";
}

recursiveScan(Config::SHARED_DRIVE . 'upload');
deleteAttachmentsWithoutFiles();

/*************************************************************************/

class DirectoryException extends Exception { }
class BadFileException extends Exception { }

function isEmptyDir($path) {
  return is_dir($path) && (count(scandir($path)) == 2);
}

function deleteFileWithMessage($path, $message) {
  global $dryRun;

  $dryRunMsg = $dryRun ? '[DRY RUN] ' : '';
  printf("%sRemoving %s: %s\n", $dryRunMsg, $path, $message);
  if (!$dryRun) {
    if (is_dir($path)) {
      rmdir($path);
    } else {
      unlink($path);
    }
  }
}

function deleteAttachmentWithMessage($a, $message) {
  global $dryRun;

  $dryRunMsg = $dryRun ? '[DRY RUN] ' : '';
  printf("%sDeleting attachment %d: %s\n", $dryRunMsg, $a->id, $message);
  if (!$dryRun) {
    $a->delete();
  }
}

function recursiveScan($path) {
  $files = scandir($path);

  foreach ($files as $file) {
    if (!Str::startsWith($file, '.')) {
      $full = $path . '/' . $file;

      // Process children first. Thus, if the whole directory is bad, it will
      // be deleted recursively.
      if (is_dir($full)) {
        recursiveScan($full);
      }

      // process $full
      try {
        if (!preg_match(FILE_PATTERN, $full, $match)) {
          throw new BadFileException('Unexplained file');
        }

        $subdir = $match['subdir'];
        $class = OBJECT_MAP[$subdir] ?? null;
        if (!$class) {
          throw new BadFileException(sprintf('Unknown subdirectory %s', $subdir));
        }

        $geometry = $match['geom'] ?? null;
        if ($geometry &&
            ($geometry != UploadTrait::$FULL_GEOMETRY) &&
            !in_array($geometry, Config::UPLOAD_SPECS[$class]['geometries'])) {
          throw new BadFileException(sprintf('Undefined %s geometry %s', $class, $geometry));
        }

        if (is_dir($full)) {
          throw new DirectoryException();
        }

        $id = $match['id'];
        $obj = Model::factory($class)->where('id', $id)->find_one();
        if (!$obj) {
          throw new BadFileException(sprintf('Unknown %s ID %s', $class, $id));
        }

        $shard = $match['shard'];
        if ($obj->getShard() != $shard) {
          throw new BadFileException(sprintf('Bad shard %s', $shard));
        }

        $extension = $match['ext'];
        if ($obj->getExtension($geometry) != $extension) {
          throw new BadFileException(
            sprintf('Bad extension %s for full-size %s', $extension, $obj->fileExtension));
        }

      } catch (DirectoryException $e) {
        // do nothing, it's fine to have directories
      } catch (BadFileException $e) {
        deleteFileWithMessage($full, $e->getMessage());
      }

      // remove legitimate directories if they are left empty
      if (isEmptyDir($full)) {
        deleteFileWithMessage($full, 'Directory is empty');
      }
    }
  }
}

// Checks that each Attachment record has a corresponding file. To save time,
// we count files and records per shard and only perform the 1:1 match if
// there is a mismatch.
function deleteAttachmentsWithoutFiles() {
  $shard = 0;

  do {
    $attachments = Model::factory('Attachment')
      ->where_gte('id', $shard * UploadTrait::$SHARD_SIZE)
      ->where_lt('id', ($shard + 1) * UploadTrait::$SHARD_SIZE)
      ->find_many();

    // This could terminate early if an entire shard is empty, but higher
    // non-empty shards exist. This seems highly unlikely.
    if (count($attachments)) {
      $files = glob(sprintf(FULL_SIZE_PATTERN, $shard));
      if (count($attachments) != count($files)) {
        printf("Found %d attachment records but only %d files for shard %d\n",
               count($attachments), count($files), $shard);
        foreach ($attachments as $a) {
          $fullPath = $a->getFileLocation(UploadTrait::$FULL_GEOMETRY);
          if (!file_exists($fullPath)) {
            deleteAttachmentWithMessage($a, 'no corresponding file');
          }
        }
      }
      $shard++;
    }
  } while (count($attachments));
}
