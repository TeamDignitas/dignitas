<?php
/**
 * This script goes through entities and their relations to compute entity
 * loyalties. An entity's loyalty is defined as a set of pairs (p_1, f_1)
 * ... (p_k, f_k) where
 *   - p_1 ... p_k are known parties
 *   - 0 ≤ f_i ≤ 1
 *   - sum(f_i) = 1
 *
 * The values of f_i are the absorbing probabilities of a Markov chain. Given
 * the entity's relations, we assign weights to each relation (e.g. a "party
 * member" relation will weigh more than a "close relative" relation, which
 * will weigh more than a "distant relative" relation).  Normalize weights so
 * they sum up to 1. Define parties as absorbing states, then compute the
 * absorption probabilities for each party using the power iteration method
 * described here:
 *
 * https://cs.stackexchange.com/a/75271/117308
 **/

require_once __DIR__ . '/../lib/Core.php';

const NUM_ITERATIONS = 100;
const THRESHOLD = 0.01; // discard fractions below this value and renormalize

// Weights of edges for various relation and entity types. If a case is not
// described here, then the relation does not yield an edge in the Markov
// graph. Each element is a (from, to) pair indicating weights for the forward
// and back edges. For instance, a company and a person should have edges in
// both directions.
const TYPE_WEIGHTS = [
  Entity::TYPE_PERSON => [
    Relation::TYPE_MEMBER => [
      Entity::TYPE_PARTY => [ 1.0, 0.0 ], // 0.0 means no edge from party to person
    ],
    Relation::TYPE_CLOSE_RELATIVE => [
      Entity::TYPE_PERSON => [ 0.5, 0.0 ], // 0.0 because there should be a reciprocal relation
    ],
    Relation::TYPE_DISTANT_RELATIVE => [
      Entity::TYPE_PERSON => [ 0.25, 0.0 ],
    ],
  ],
];

LocaleUtil::change('en_US.utf8');

// Build a map such that $partyIds[k] = true iff Entity ID k is a party.
$parties = Entity::get_all_by_type_status(Entity::TYPE_PARTY, Ct::STATUS_ACTIVE);
$partyIds = Util::objectProperty($parties, 'id');

// Load all relations and convert them to (from, to, weight) edges. Map these
// by from. The result is an array of $from => [ [ $to, $weight ], ... ].
$relations = Model::factory('Relation')
  ->table_alias('r')
  ->select('r.*')
  ->select('f.type', 'fromType')
  ->select('t.type', 'toType')
  ->join('entity', ['r.fromEntityId', '=', 'f.id'], 'f')
  ->join('entity', ['r.toEntityId', '=', 't.id'], 't')
  ->where('f.status', Ct::STATUS_ACTIVE)
  ->where('t.status', Ct::STATUS_ACTIVE)
  ->find_many();

// first store relations as [ $from => [ $to => $weight ]];
$forwardMap = [];

foreach ($relations as $r) {
  $factors = TYPE_WEIGHTS[$r->fromType][$r->type][$r->toType] ?? null;
  if ($factors) {
    $weight = $r->getWeight();

    addEdge($forwardMap, $r->fromEntityId, $r->toEntityId, $weight * $factors[0]);
    addEdge($forwardMap, $r->toEntityId, $r->fromEntityId, $weight * $factors[1]);
  }
}

// normalize weights so the sum of outgoing weights is 1
normalizeMap($forwardMap);

// flip the map to get the weights map by toEntityId
$backMap = flip($forwardMap);

// make parties absorbing
foreach ($partyIds as $id) {
  $backMap[$id][$id] = 1.0;
}

$entities = Entity::get_all_by_type_status(Entity::TYPE_PERSON, Ct::STATUS_ACTIVE);
foreach ($entities as $e) {
  // compute x = x_0 * P^T
  $loyalties = multiply($e, $backMap);
  normalizeLoyalties($loyalties);
  saveLoyalties($e, $loyalties);
}

/*************************************************************************/

function addEdge(&$map, $from, $to, $weight) {
  if ($weight) {
    $existing = $map[$from][$to] ?? 0.0;
    $map[$from][$to] = $existing + $weight;
  }
}

function normalizeMap(&$map) {
  foreach ($map as $from => $row) {
    $sum = 0.0;
    foreach ($row as $weight) {
      $sum += $weight;
    }
    foreach ($row as $to => $weight) {
      $map[$from][$to] = $weight / $sum;
    }
  }
}

function flip (&$map) {
  // now build the back map
  $result = [];
  foreach ($map as $from => $row) {
    foreach ($row as $to => $weight) {
      $result[$to][$from] = $weight;
    }
  }
  return $result;
}

function multiply($e, &$map) {
  $x = [ $e->id => 1.0 ];

  for ($i = 0; $i < NUM_ITERATIONS; $i++)  {
    $y = [];
    foreach ($map as $to => $col) {
      // compute the dot product of $x and $row
      $sum = 0.0;
      foreach ($col as $from => $weight) {
        if (isset($x[$from])) {
          $sum += $weight * $x[$from];
        }
      }
      if ($sum) {
        $y[$to] = $sum;
      }
    }
    $x = $y;
  }

  return $x;
}

function normalizeLoyalties(&$loyalties) {
  if (empty($loyalties)) {
    return;
  }

  $sum = 0;
  foreach ($loyalties as $id => $value) {
    if ($value < THRESHOLD) {
      unset($loyalties[$id]);
    } else {
      $sum += $value;
    }
  }

  foreach ($loyalties as $id => $value) {
    $loyalties[$id] /= $sum;
  }
}

function saveLoyalties($entity, $loyalties) {
  Loyalty::delete_all_by_fromEntityId($entity->id);
  foreach ($loyalties as $toEntityId => $value) {
    $l = Loyalty::create($entity->id, $toEntityId, $value);
    $l->save();
  }
}
