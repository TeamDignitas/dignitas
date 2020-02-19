<?php

/**
 * This class further extends Precursor which in turn extends Paris's Model
 * class. ProtoExt gives access to extension tables.
 */
class ProtoExt extends Precursor {

  /**
   * Foreign key field. Child classes should override this.
   */
  const FOREIGN_KEY_FIELD = null;

  /**
   * Caches looked up values.
   */
  private static $cache = [];

  /**
   * Updates an extended field. Inserts an extended record if one does not exist.
   *
   * @param int $foreignValue ID value in main table.
   * @param string $field Field to set.
   * @param mixed $value New value of $field
   */
  static function setField($foreignValue, $field, $value) {
    $class = get_called_class();
    $foreignKey = static::FOREIGN_KEY_FIELD;

    $obj = self::getFromCacheOrDb($class, $foreignKey, $foreignValue);

    if (!$obj) {
      // create a new extended record
      $obj = Model::factory($class)->create();
      $obj->$foreignKey = $foreignValue;
    }

    $obj->$field = $value;
    $obj->save();

    // add it to the cache
    self::$cache[$class][$foreignValue] = $obj;
  }

  /**
   * Retrieves an extended field value.
   *
   * @param int $foreignValue ID value in main table.
   * @param string $field Field to get.
   * @param mixed $default Value to return if there is no record.
   */
  static function getField($foreignValue, $field, $default = null) {
    $class = get_called_class();
    $foreignKey = static::FOREIGN_KEY_FIELD;

    $obj = self::getFromCacheOrDb($class, $foreignKey, $foreignValue);

    // add it to the cache
    self::$cache[$class][$foreignValue] = $obj;

    return $obj->$field ?? $default;
  }

  /**
   * Retrieves the extended record from cache (if available) or from the
   * database (if available).
   *
   * @param $class Class of extended record.
   * @param $foreignKey Field pointing to the main table.
   * @param int $foreignValue ID value in main table.
   */
  private static function getFromCacheOrDb($class, $foreignKey, $foreignValue) {
    // cache lookup first
    $obj = self::$cache[$class][$foreignValue] ?? null;

    // DB lookup next
    if (!$obj) {
      $obj = Model::factory($class)
        ->where($foreignKey, $foreignValue)
        ->find_one();
    }

    return $obj;
  }

}
