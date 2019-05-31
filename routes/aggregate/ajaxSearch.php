<?php

const LIMIT = 10;
const SCORE_ENTITY = 10;
const SCORE_ENTITY_BEGINNING_OF_WORD = 20;
const SCORE_TAG = 30;

$q = Request::get('q');

$results = [];

// load entities by substring match (name)
$entities = Model::factory('Entity')
  ->where_like('name', "%{$q}%")
  ->limit(LIMIT)
  ->find_many();

foreach ($entities as $e) {
  $results[] = new EntitySearchResult($e, $q);
}

// load tags by prefix match
$tags = Model::factory('Tag')
  ->where_like('value', "{$q}%")
  ->limit(LIMIT)
  ->find_many();

foreach ($tags as $t) {
  $results[] = new TagSearchResult($t, SCORE_TAG);
}

$output = [
  'results' => [],
];
foreach ($results as $r) {
  $output['results'][] = [
    'id' => $r->getId(),
    'text' => $r->getDisplayText(),
  ];
}

header('Content-Type: application/json');
print json_encode($output);

/*************************************************************************/

abstract class GenericSearchResult {
  protected $score;

  abstract function getId();
  abstract function getDisplayText();
}

class EntitySearchResult extends GenericSearchResult {
  private $entity;

  function __construct($entity, $query) {
    // better scores at word boundaries
    $regex = sprintf('/\b%s/ui', $query);
    $score = preg_match($regex, $entity->name)
      ? SCORE_ENTITY_BEGINNING_OF_WORD
      : SCORE_ENTITY;

    $this->entity = $entity;
    $this->score = $score;
  }

  function getId() {
    return $this->entity->id;
  }

  function getDisplayText() {
    return $this->entity->name . ' (' . $this->score . ')';
  }

}

class TagSearchResult extends GenericSearchResult {
  private $tag;

  function __construct($tag) {
    $this->tag = $tag;
    $this->score = SCORE_TAG;
  }

  function getId() {
    return $this->tag->id;
  }

  function getDisplayText() {
    return $this->tag->value . ' (' . $this->score . ')';
  }

}
