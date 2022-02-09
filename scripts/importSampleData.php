#!/usr/bin/php
<?php
/**
 * This script imports a minimal usable dataset. This helps bootstrap a
 * development installation.
 **/

require_once __DIR__ . '/../lib/Core.php';

const APACHE_USER_GROUP = 'http.http';

LocaleUtil::change('en_US.utf8');

// ask to be run as root
if (posix_getuid() != 0) {
  die("Please run this script as root. It needs to change files on the " .
      "shared drive, which is owned by the Apache user.\n");
}

// parse command line options
$opts = getopt('a:');
$apacheUserGroup = $opts['a'] ?? APACHE_USER_GROUP;

// warn that the end of the world is coming
print "A few notes about this script:

1. Please ensure that your codebase is up to date: run \"git pull\".
2. Please ensure that your Config.php is correct.
3. This script will overwrite the entire database and shared drive.
4. This script will change ownership of the shared drive to {$apacheUserGroup}. If that is not
   your Apache user and group, use the -a<user.group> option to specify yours.

";

$answer = readline('Is this OK? Type "yes" to proceed, anything else to abort: ');
if ($answer !== 'yes') {
  exit;
}

// download both files before changing anything
$url = Config::DATABASE_SCHEMA_URL;
$sql = file_get_contents($url)
  or die("Cannot download database schema from {$url}\n");
// remove DEFINER from trigger definitions as it requires the MySQL user to
// have super privileges
$sql = preg_replace('/\sDEFINER=`[^`]*`@`[^`]*`/', '', $sql);

$url = Config::SAMPLE_DATA_URL;
$json = file_get_contents($url)
  or die("Cannot download sample data from {$url}\n");

// save the schema to a file and import it
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
      : $obj->getFileLocation($class::$FULL_GEOMETRY);
    @mkdir(dirname($path), 0777, true);
    file_put_contents($path, $contents);
  }
}

// change ownership
$chownCmd = sprintf('chown -R %s %s', $apacheUserGroup, Config::SHARED_DRIVE);
exec($chownCmd);
