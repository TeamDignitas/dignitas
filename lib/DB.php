<?php

require_once Core::portable(__DIR__ . '/third-party/idiorm-1.5.6.php');
require_once Core::portable(__DIR__ . '/third-party/paris-1.5.6.php');

class DB {
  static $dsn;
  static $user;
  static $password;
  static $host;
  static $database;

  static function init() {
    $dsn = sprintf('mysql:host=%s;dbname=%s', Config::DB_HOST, Config::DB_DATABASE);
    ORM::configure($dsn);
    ORM::configure('username', Config::DB_USER);
    ORM::configure('password', Config::DB_PASSWORD);

    // This allows var_dump(ORM::get_query_log()) or var_dump(ORM::get_last_query())
    // ORM::configure('logging', true);

    ORM::configure('driver_options', [
      PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    ]);
  }

  static function executeSqlFile($filename, $database = null) {
    $filename = realpath(Core::portable($filename));
    $command = sprintf('mysql -h %s -u %s %s %s < %s',
                       Config::DB_HOST,
                       Config::DB_USER,
                       Config::DB_DATABASE,
                       Config::DB_PASSWORD ? ('-p' . Config::$password) : '',
                       $filename);
    var_dump($command);
    OS::executeAndAssert($command);
  }

  static function tableExists($tableName) {
    $r = ORM::for_table($tableName)
      ->raw_query("show tables like '$tableName'")
      ->find_one();
    return ($r !== false);
  }

}
