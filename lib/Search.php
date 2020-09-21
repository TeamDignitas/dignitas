<?php

class Search {

  // limit per object type - statements, entities and tags
  const LIMIT = 10;

  static function run($query, $limit = self::LIMIT) {
    $escapedQuery = addslashes($query);

    list ($numStatementPages, $statements) =
      self::searchStatements([ 'term' => $escapedQuery ], 'createDate desc', 1);

    $results = [
      'entities' => self::searchEntities($escapedQuery, 0, $limit),
      'statements' => $statements,
      'numStatementPages' => $numStatementPages,
      'tags' => self::searchTags($escapedQuery, $limit),
    ];
    return $results;
  }

  /**
   * Load entities by substring match at word boundary. MariaDB has a problem
   * with regexp and collations, so this one-liner won't work:
   *
   *   name regexp "[[:<:]]%s" collate utf8mb4_general_ci
   *
   * @param string $escapedQuery String to match.
   * @param int $exceptId Skip this entity even if it matches $escapedQuery.
   * @param int $limit Return at most this many results.
   * @return Entity[]
   */
  static function searchEntities($escapedQuery, $exceptId = 0, $limit = self::LIMIT) {
    // Suppress warning due to bad argument order in Idiorm's where_any_is()
    return @Model::factory('Entity')
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
   * Searches and sorts statements.
   *
   * @param array $filters A map of field => value. See code for field definitions.
   * @param string $order A field + direction such as 'createDate desc'.
   * @param int $page If zero, load up to $pageSize results. If nonzero, load
   *   the given page assuming each page has size $pageSize.
   * @param int $pageSize If $page is zero, this is the result limit. If $page
   *   is nonzero, this is the size of each page.
   * @return array A tuple of [ numPages, array(Statement)]. If $page is zero,
   *   then numPages will also be zero.
   */
  static function searchStatements(
    $filters,
    $order = 'createDate desc',
    $page = 0,
    $pageSize = null) {

    $query = Model::factory('Statement')
      ->where_not_equal('status', Ct::STATUS_PENDING_EDIT);

    foreach ($filters as $field => $value) {
      if (!empty($value)) {
        switch ($field) {
          case 'entityId':
            $query = $query->where('entityId', $value);
            break;
          case 'exceptId':
            $query = $query->where_not_equal('id', $value);
            break;
          case 'maxDate':
            $query = $query->where_lte('dateMade', $value);
            break;
          case 'minDate':
            $query = $query->where_gte('dateMade', $value);
            break;
          case 'term':
            $query = $query->where_like('summary', "%{$value}%");
            break;
          case 'verdicts':
            $query = $query->where_in('verdict', $value);
            break;
          default: die('Bad filter field.');
        }
      }
    }

    if (!$pageSize) {
      $pageSize = ($page > 0) ? Config::STATEMENT_LIST_PAGE_SIZE : self::LIMIT;
    }

    if ($page > 0) {
      $numPages = ceil($query->count() / $pageSize);
      $query = $query->offset(($page - 1) * $pageSize);
    } else {
      $numPages = 0;
    }

    $statements = $query
      ->order_by_expr($order)
      ->limit($pageSize)
      ->findMany();
    return [ $numPages, $statements ];
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
