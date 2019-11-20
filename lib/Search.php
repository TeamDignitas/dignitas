<?php

class Search {

  // limit per object type - entities and tags
  const LIMIT = 10;

  static function run($query, $limit = self::LIMIT) {
    $escapedQuery = addslashes($query);
    $results = [
      'entities' => self::searchEntities($escapedQuery, 0, $limit),
      'tags' => self::searchTags($escapedQuery, $limit),
    ];
    return $results;
  }

  // Load entities by substring match at word boundary. MariaDB has a problem
  // with regexp and collations, so this one-liner won't work:
  //
  //   name regexp "[[:<:]]%s" collate utf8mb4_general_ci
  static function searchEntities($escapedQuery, $exceptId = 0, $limit = self::LIMIT) {
    return Model::factory('Entity')
      ->table_alias('e')
      ->select('e.*')
      ->distinct()
      ->left_outer_join('alias', ['e.id', '=', 'a.entityId'], 'a')
      ->where_not_equal('e.id', $exceptId ?? 0)
      ->where('e.status', Ct::STATUS_ACTIVE)
      ->where_any_is([
        [ 'e.name' => "{$escapedQuery}%" ],
        [ 'e.name' => "% {$escapedQuery}%" ],
        [ 'e.name' => "%-{$escapedQuery}%" ],
        [ 'a.name' => "{$escapedQuery}%" ],
      ], 'like')
      ->order_by_asc('e.name')
      ->limit($limit)
      ->find_many();
  }

  /**
   * Loads statements by substring match.
   *
   * @param string $escapedQuery Substring query, already escaped
   * @param int $exceptId ID to exclude from results (useful for duplicate flags)
   * @param int $limit Maximum results to return
   */
  static function searchStatements($escapedQuery, $exceptId = 0, $limit = self::LIMIT) {
    return Model::factory('Statement')
      ->where_like('summary', "%{$escapedQuery}%")
      ->where_not_equal('id', $exceptId)
      ->order_by_asc('summary')
      ->limit($limit)
      ->find_many();
  }

  // load tags by prefix match
  static function searchTags($escapedQuery, $limit = self::LIMIT) {
    return Model::factory('Tag')
      ->where_like('value', "{$escapedQuery}%")
      ->order_by_asc('value')
      ->limit($limit)
      ->find_many();
  }

}
