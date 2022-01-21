<?php

class Search {

  // limit per object type - statements, entities, tags and regions
  const LIMIT = 10;

  static function run($query, $limit = self::LIMIT) {
    $escapedQuery = addslashes($query);

    list ($numStatementPages, $statements) =
      self::searchStatements([ 'term' => $escapedQuery ], Ct::SORT_VERDICT_DATE_DESC, 1);
    list ($numEntityPages, $entities) =
      self::searchEntities([ 'term' => $escapedQuery ], Ct::SORT_NAME_ASC, 1);
    $relations =
      self::searchRelations([ 'term' => $escapedQuery ], Ct::SORT_NAME_ASC);

    $results = [
      'entities' => $entities,
      'numEntityPages' => $numEntityPages,
      'statements' => $statements,
      'numStatementPages' => $numStatementPages,
      'relations' => $relations,
      'tags' => self::searchTags($escapedQuery, $limit),
      'regions' => self::searchRegions($escapedQuery, $limit),
    ];
    $results['empty'] =
      empty($results['entities']) &&
      empty($results['statements']) &&
      empty($results['relations']) &&
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
    $order = Ct::SORT_VERDICT_DATE_DESC,
    $page = 0,
    $pageSize = null) {

    $query = Model::factory('Statement')
      ->table_alias('s')
      ->select('s.*')
      ->where_not_in('s.status', [ Ct::STATUS_PENDING_EDIT, Ct::STATUS_DRAFT ]);
    if (!User::may(User::PRIV_DELETE_STATEMENT)) {
      $query = $query->where_not_equal('s.status', Ct::STATUS_DELETED);
    }

    self::parseSearchTerm($filters, $query);

    foreach ($filters as $field => $value) {
      if ($field == 'closed') {
        $query = $value
          ? $query = $query->where('s.status', Ct::STATUS_CLOSED)
          : $query = $query->where_not_equal('s.status', Ct::STATUS_CLOSED);
      } else if ($field == 'duplicate') {
        $query = $value
          ? $query = $query->where_not_equal('s.duplicateId', 0)
          : $query = $query->where('s.duplicateId', 0);
      } else if ($field == 'minAnswers') {
        $query = $query->having_raw("numAnswers >= $value");
      } else if ($field == 'maxAnswers') {
        $query = $query->having_raw("numAnswers <= $value");
      } else if ($field == 'minScore') {
        $query = $query->having_raw("score >= $value");
      } else if ($field == 'maxScore') {
        $query = $query->having_raw("score <= $value");
      } else if (!empty($value)) {
        // for the remaining fields ignore empty values
        switch ($field) {
          case 'type':
            $query = $query->where('s.type', $value);
            break;
          case 'entityId':
            $query = $query->where('s.entityId', $value);
            break;
          case 'regionId':
            $query = $query->where('s.regionId', $value);
            break;
          case 'exceptId':
            $query = $query->where_not_equal('s.id', $value);
            break;
          case 'maxDate':
            $query = $query->where_lte('s.dateMade', $value);
            break;
          case 'minDate':
            $query = $query->where_gte('s.dateMade', $value);
            break;
          case 'term':
            $query = $query->where_like('s.summary', "%{$value}%");
            break;
          case 'verdicts':
            $query = $query->where_in('s.verdict', $value);
            break;
          default: die('Bad filter field.');
        }
      }
    }

    if (!$pageSize) {
      $pageSize = ($page > 0) ? Config::STATEMENT_LIST_PAGE_SIZE : self::LIMIT;
    }

    if ($page > 0) {
      // Cannot simply run $query->count() here because there is grouping for
      // numAnswers. Therefore, count(*) would return every row.
      $count = (clone $query)->select_expr('count(*) over ()', 'numRows')->find_one();
      $numRows = $count->numRows ?? 0;
      $numPages = ceil($numRows / $pageSize);
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

  /**
   * If $filters['terms'] exists and contains keywords, then extracts the
   * keywords as separate filters. If this requires joins on the query, then
   * modifies the query. Otherwise does nothing.
   */
  static function parseSearchTerm(array &$filters, object &$query) {
    if (empty($filters['term'])) {
      return;
    }

    $helper = new KeywordHelper($filters['term']);
    $helper->run();
    $filters = array_merge($filters, $helper->getFilters());

    if (isset($filters['minAnswers']) || isset($filters['maxAnswers'])) {
      $query = $query
        ->select_expr('count(a.id)', 'numAnswers')
        ->raw_join(
          'left join answer',
          sprintf('a.statementId = s.id and a.status = %s', Ct::STATUS_ACTIVE),
          'a')
        ->group_by('s.id');
    }

    if (isset($filters['minScore']) || isset($filters['maxScore'])) {
      $query = $query
        ->select_expr('ifnull(se.score, 0)', 'score')
        ->left_outer_join('statement_ext', [ 's.id', '=', 'se.statementId' ], 'se');
    }
  }

  /**
   * Searches entity relationships.
   *
   * @param array $filters A map of field => value.
   *   - term: mandatory; the search term
   *   - active: optional; only load active (ongoing) relationships
   */
  static function searchRelations($filters, $order = Ct::SORT_NAME_ASC) {

    // Idiorm cannot retrieve individual objects from a tuple. Fields with
    // identical names only appear once. So we do this in stages by object type.

    $query = $filters['term'];
    if (!$query) {
      return [];
    }

    // Stage 1: relation types
    $rts = Model::factory('RelationType')
      ->where_any_is([
        [ 'name' => "{$query}%" ],
        [ 'name' => "% {$query}%" ],
        [ 'name' => "%-{$query}%" ],
      ], 'like')
      ->find_many();
    $relationTypeIds = Util::objectProperty($rts, 'id');
    $relationTypeMap = Util::mapById($rts);

    // Stage 2: relations
    $relations = Model::factory('Relation')
      ->where_in('relationTypeId', $relationTypeIds ?: [ 0 ]);

    foreach ($filters as $field => $value) {
      if (!empty($value)) {
        switch ($field) {
          case 'active':
            $today = Time::today();
            $relations = $relations
              ->where_raw('(`endDate` = ? OR `endDate` >= ?)', [ '0000-00-00', $today ]);
            break;
          case 'activeDate':
            $relations = $relations
              ->where_raw('(`startDate` != ? AND `startDate` <= ?)', [ '0000-00-00', $value ])
              ->where_raw('(`endDate` = ? OR `endDate` >= ?)', [ '0000-00-00', $value ]);
            break;
          case 'term':
            break; // processed before
          default: die('Bad filter field.');
        }
      }
    }
    $relations = $relations->find_many();

    // Stage 3: necessary, active entities
    // Load them all at once to minimize the number of queries.
    $entityIds = array_unique(array_merge(
      Util::objectProperty($relations, 'fromEntityId'),
      Util::objectProperty($relations, 'toEntityId')));

    $entities = Model::factory('Entity')
      ->where_in('id', $entityIds ?: [ 0 ])
      ->where('status', Ct::STATUS_ACTIVE)
      ->find_many();
    $entityMap = Util::mapById($entities);

    // Stage 4: group relations by (relationType, toEntity)
    $helper = []; // $helper[$relationTypeId][$toEntityId] is a position in $results
    $results = [];
    foreach ($relations as $rel) {
      if (isset($entityMap[$rel->fromEntityId]) &&
          isset($entityMap[$rel->toEntityId])) {

        $pos = $helper[$rel->relationTypeId][$rel->toEntityId] ?? null;
        if ($pos === null) {
          $pos = $helper[$rel->relationTypeId][$rel->toEntityId] = count($results);
          $results[] = [
            'relationType' => $relationTypeMap[$rel->relationTypeId],
            'toEntity' => $entityMap[$rel->toEntityId],
            'data' => [],
          ];
        }

        $results[$pos]['data'][] = [
          'relation' => $rel,
          'fromEntity' => $entityMap[$rel->fromEntityId],
        ];
      }
    }

    // Stage 5: sort groups and data within each group
    $mult = ($order == Ct::SORT_NAME_DESC) ? -1 : 1;
    usort($results, function($a, $b) use ($mult) {
      $rtA = mb_strtolower($a['relationType']->name);
      $rtB = mb_strtolower($b['relationType']->name);
      if ($rtA != $rtB) {
        return $mult * ($rtA <=> $rtB);
      }

      $entityA = mb_strtolower($a['toEntity']->name);
      $entityB = mb_strtolower($b['toEntity']->name);
      return $mult * ($entityA <=> $entityB);
    });

    foreach ($results as &$rec) {
      usort($rec['data'], function($a, $b) {
        $dateCmp = $a['relation']->newerThan($b['relation']);
        if ($dateCmp) {
          return $dateCmp;
        }

        $entityA = mb_strtolower($a['fromEntity']->name);
        $entityB = mb_strtolower($b['fromEntity']->name);
        return $entityA <=> $entityB;
      });
    }

    return $results;
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

/**
 * A helper class that defines methods for extracting search term keywords
 * like "answers:2..8". The callbacks need access to the $filters array in
 * order to add new filters (in this case: minAnswers=2, maxAnswers=8). The
 * manual for preg_replace_callback recommends using closures, but those make
 * the wrapping function unreasonably long.
 */
class KeywordHelper {
  private array $filters = [];
  private string $term;

  function __construct(string $term) {
    $this->term = $term;
  }

  /**
   * Returns the parsed filters, including the remaining search term.
   */
  function getFilters() {
    return $this->filters;
  }

  function run() {
    $this->runFilter(
      sprintf('/\b%s:(1|0|%s|%s)\b/',
              _('search-keyword-closed'), _('search-keyword-yes'), _('search-keyword-no')),
      [$this, 'callbackClosed']);
    $this->runFilter(
      sprintf('/\b%s:(1|0|%s|%s)\b/',
              _('search-keyword-duplicate'), _('search-keyword-yes'), _('search-keyword-no')),
      [$this, 'callbackDuplicate']);
    // parse answers:(x|x..|x..y|..y)
    $this->runFilter(
      sprintf('/\b%s:(\d+\.\.\d+|\d+\.\.|\d+|\.\.\d+)(?=\s|$)/',
              _('search-keyword-answers')),
      [$this, 'callbackAnswers']);
    // parse score:(x|x..|x..y|..y) (all integers can be signed)
    $this->runFilter(
      sprintf('/\b%s:([-+]?\d+\.\.[-+]?\d+|[-+]?\d+\.\.|[-+]?\d+|\.\.[-+]?\d+)(?=\s|$)/',
              _('search-keyword-score')),
      [$this, 'callbackScore']);

    // collapse whitespace and set the remaining search term
    $this->filters['term'] = trim(preg_replace('/\s+/', ' ', $this->term));
  }

  private function runFilter(string $regex, callable $callback) {
    $this->term = preg_replace_callback($regex, $callback, $this->term);
  }

  function callbackClosed($matches) {
    $this->filters['closed'] = in_array($matches[1], [ '1', _('search-keyword-yes')]);
  }

  function callbackDuplicate($matches) {
    $this->filters['duplicate'] = in_array($matches[1], [ '1', _('search-keyword-yes')]);
  }

  function callbackAnswers($matches) {
    $this->parseRange($matches, 'minAnswers', 'maxAnswers');
  }

  function callbackScore($matches) {
    $this->parseRange($matches, 'minScore', 'maxScore');
  }

  /**
   * Parses ranges in one of the form x, x.., x..y or ..y.
   */
  function parseRange($matches, $minField, $maxField) {
    $pair = explode('..', $matches[1]);
    if (!isset($pair[1])) {
      $pair[1] = $pair[0];
    }

    if ($pair[0] !== '') {
      $this->filters[$minField] = $pair[0];
    } else {
      unset($this->filters[$minField]); // clear in case there were multiple matches
    }
    if ($pair[1] !== '') {
      $this->filters[$maxField] = $pair[1];
    } else {
      unset($this->filters[$maxField]);
    }
  }

}
