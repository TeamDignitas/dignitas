<?php
/**
 * This script imports a minimal usable dataset. This helps bootstrap a
 * development installation.
 **/

require_once __DIR__ . '/../lib/Core.php';

LocaleUtil::change('en_US.utf8');

// Download both files before changing anything
$url = Config::DATABASE_SCHEMA_URL;
$sql = file_get_contents($url)
  or die("Cannot download database schema from {$url}\n");

$url = Config::SAMPLE_DATA_URL;
$json = file_get_contents($url)
  or die("Cannot download sample data from {$url}\n");

// Save the schema to a file and import it
$sqlFileName = tempnam(Config::TMP_DIR, 'dignitas_');
file_put_contents($sqlFileName, $sql);
DB::executeSqlFile($sqlFileName);
unlink($sqlFileName);

$data = json_decode($json, true);

// import data
foreach ($data['objects'] as $class => $objects) {
  foreach ($objects as $rec) {
    Log::debug('Saving %s#%d', $class, $rec['id']);
    $obj = Model::factory($class)->create();
    $obj->set($rec);
    $obj->save();
  }
}

// remove existing shared files
$rmCmd = sprintf('rm -rf %s/*', Config::SHARED_DRIVE);
exec($rmCmd);

// save downloaded files
foreach ($data['files'] as $class => $files) {
  foreach ($files as $id => $base64) {
    Log::debug('Saving file for %s#%d', $class, $id);
    $contents = base64_decode($base64);
    $obj = Model::factory($class)->where('id', $id)->find_one();
    $path = ($class == 'StaticResource')
      ? $obj->getFilePath()
      : $obj->getFileLocation(UploadTrait::$FULL_GEOMETRY);
    @mkdir(dirname($path), 0777, true);
    file_put_contents($path, $contents);
  }
}
