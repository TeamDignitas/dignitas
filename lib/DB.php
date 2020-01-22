<?php

require_once 'third-party/idiorm-1.5.6.php';
require_once 'third-party/paris-1.5.6.php';

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

    // choose a random 63-bit request_id; should be reasonably distinct
    ORM::configure('driver_options', [
      PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8mb4',
    ]);
    self::pickRequestId();
  }

  /**
   * Picks a random request ID for the duration of this request. This ID will
   * not necessarily be unique, but should be extremely likely distinct in a
   * short time frame. The request ID allows us to check the revision tables
   * to track all the changes triggered by a save operation. On some occasions
   * we change this ID during the request. Specifically, when we create a
   * pending edit, we first clone the original object hierarchy, then change
   * the request ID and only then apply the user modifications.
   */
  static function pickRequestId() {
    $r = random_int(0, PHP_INT_MAX); // unsigned 63-bit
    self::execute("set @request_id = {$r}");
  }

  // Returns a DB result set that you can iterate with foreach ($result as $row)
  static function execute($query, $fetchStyle = PDO::FETCH_BOTH) {
    return ORM::get_db()->query($query, $fetchStyle);
  }

  static function executeSqlFile($filename, $database = null) {
    $filename = realpath(Core::portable($filename));
    $command = sprintf('mysql -h %s -u %s %s %s < %s',
                       Config::DB_HOST,
                       Config::DB_USER,
                       Config::DB_DATABASE,
                       Config::DB_PASSWORD ? ('-p' . Config::$password) : '',
                       $filename);
    OS::executeAndAssert($command, $ignored);
  }

  static function tableExists($tableName) {
    $r = ORM::for_table($tableName)
      ->raw_query("show tables like '$tableName'")
      ->find_one();
    return ($r !== false);
  }

}
