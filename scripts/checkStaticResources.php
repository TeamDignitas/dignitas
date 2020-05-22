<?php
/**
 * This script goes through all the static resources and reports:
 *
 * 1. orphan files (can also delete them with the -f option);
 * 2. missing underlying files.
 **/

require_once __DIR__ . '/../lib/Core.php';

LocaleUtil::change('en_US.utf8');

$opts = getopt('f', ['fix']);
$fix = isset($opts['f']) || isset($opts['fix']);

// hash all the filenames, so  $files[<file_name>] = true for all files (recursively)
$path = sprintf(StaticResource::DIR_PATTERN, Config::SHARED_DRIVE);
$dirIter = new RecursiveDirectoryIterator($path);
$fileIter = new RecursiveIteratorIterator($dirIter);
$regexIter = new RegexIterator($fileIter, '/[^.]$/');

$files = [];
foreach ($regexIter as $file) {
  $files[$file->getPathname()] = true;
}

$srs = StaticResource::loadAll();
foreach ($srs as $sr) {
  $path = $sr->getFilePath();
  if (isset($files[$path])) {
    unset($files[$path]);
  } else {
    Log::warning('StaticResource #%d has no underlying file [%s].',
                 $sr->id, $path);
  }
}

// report and/or delete any remaining files
foreach ($files as $file => $ignored) {
  if ($fix) {
    Log::warning('Deleting orphan file [%s].', $file);
    unlink($file);
  } else {
    Log::warning('File [%s] is an orphan, use -f to delete.', $file);
  }
}
