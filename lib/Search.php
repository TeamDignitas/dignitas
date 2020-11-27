<?php

class Search {

  // limit per object type - statements, entities, tags and regions
  const LIMIT = 10;

  static function run($query, $limit = self::LIMIT) {
    $escapedQuery = addslashes($query);

    list ($numStatementPages, $statements) =
      self::searchStatements([ 'term' => $escapedQuery ], Ct::SORT_CREATE_DATE_DESC, 1);
    list ($numEntityPages, $entities) =
      self::searchEntities([ 'term' => $escapedQuery ], Ct::SORT_NAME_ASC, 1);

    $results = [
      'entities' => $entities,
      'numEntityPages' => $numEntityPages,
      'statements' => $statements,
      'numStatementPages' => $numStatementPages,
      'tags' => self::searchTags($escapedQuery, $limit),
      'regions' => self::searchRegions($escapedQuery, $limit),
    ];
    $results['empty'] =
      empty($results['entities']) &&
      empty($results['statements']) &&
      empty($results['tags']) &&
      empty($results['regions']);
    return $results;
  }

  /**
   * Searches and sorts entities.
   *
   * @param array $filters A map of field => value. See code for field definitions.
   * @param string $order One of the Ct::SORT_* constants
   * @param int $page If zero, load up to $pageSize results. If nonzero, load
   *   the given page assuming each page has size $pageSize.
   * @param int $pageSize If $page is zero, this is the result limit. If $page
   *   is nonzero, this is the size of each page.
   * @return array A tuple of [ numPages, array(Entity)]. If $page is zero,
   *   then numPages will also be zero.
   */
  static function searchEntities(
    $filters,
    $order = Ct::SORT_NAME_ASC,
    $page = 0,
    $pageSize = null) {

    // Suppress warning due to bad argument order in Idiorm's where_any_is()
    $query = @Model::factory('Entity')
      ->table_alias('e')
      ->select('e.*')
      ->distinct()
      ->left_outer_join('alias', ['e.id', '=', 'a.entityId'], 'a')
      ->where('e.status', Ct::STATUS_ACTIVE);

    foreach ($filters as $field => $value) {
      if (!empty($value)) {
        switch ($field) {
          case 'exceptId':
            $query = $query->where_not_equal('id', $value);
            break;
          case 'regionId':
            $query = $query->where('regionId', $value);
            break;
          case 'term':
            // Load entities by substring match at word boundary. MariaDB has
            // a problem with regexp and collations, so this one-liner won' work:
            //
            //   name regexp "[[:<:]]%s" collate utf8mb4_general_ci
            $query = $query->where_any_is([
              [ 'e.name' => "{$value}%" ],
              [ 'e.name' => "% {$value}%" ],
              [ 'e.name' => "%-{$value}%" ],
              [ 'a.name' => "{$value}%" ],
            ], 'like');
            break;
          default: die('Bad filter field.');
        }
      }
    }

    if (!$pageSize) {
      $pageSize = ($page > 0) ? Config::ENTITY_LIST_PAGE_SIZE : self::LIMIT;
    }

    if ($page > 0) {
      $numPages = ceil($query->count() / $pageSize);
      $query = $query->offset(($page - 1) * $pageSize);
    } else {
      $numPages = 0;
    }

    $sqlOrder = Ct::SORT_SQL[$order];
    $entities = $query
      ->order_by_expr($sqlOrder)
      ->limit($pageSize)
      ->find_many();
    return [ $numPages, $entities ];
  }

  /**
   * Searches and sorts statements.
   *
   * @param array $filters A map of field => value. See code for field definitions.
   * @param string $order One of the Ct::SORT_* constants
   * @param int $page If zero, load up to $pageSize results. If nonzero, load
   *   the given page assuming each page has size $pageSize.
   * @param int $pageSize If $page is zero, this is the result limit. If $page
   *   is nonzero, this is the size of each page.
   * @return array A tuple of [ numPages, array(Statement)]. If $page is zero,
   *   then numPages will also be zero.
   */
  static function searchStatements(
    $filters,
    $order = Ct::SORT_CREATE_DATE_DESC,
    $page = 0,
    $pageSize = null) {

    $query = Model::factory('Statement')
      ->where_not_equal('status', Ct::STATUS_PENDING_EDIT);
    if (!User::may(User::PRIV_DELETE_STATEMENT)) {
      $query = $query->where_not_equal('status', Ct::STATUS_DELETED);
    }

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

    $sqlOrder = Ct::SORT_SQL[$order];
    $statements = $query
      ->order_by_expr($sqlOrder)
      ->limit($pageSize)
      ->find_many();
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

  // load regions by prefix match
  static function searchRegions($escapedQuery, $limit = self::LIMIT) {
    return Model::factory('Region')
      ->where_like('name', "{$escapedQuery}%")
      ->order_by_asc('name')
      ->limit($limit)
      ->find_many();
  }

}
