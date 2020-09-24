#!/usr/bin/php
<?php
/**
 * This script goes through entities and their relations to compute entity
 * loyalties. An entity's loyalty is defined as a set of pairs (p_1, f_1)
 * ... (p_k, f_k) where
 *   - p_1 ... p_k are known sinks (i.e. parties)
 *   - 0 ≤ f_i ≤ 1
 *   - sum(f_i) = 1
 *
 * The values of f_i are the absorbing probabilities of a Markov chain. Given
 * the entity's relations, we assign weights to each relation (e.g. a "party
 * member" relation will weigh more than a "close relative" relation, which
 * will weigh more than a "distant relative" relation).  Normalize weights so
 * they sum up to 1. Define sinks as absorbing states, then compute the
 * absorption probabilities for each sink using the power iteration method
 * described here:
 *
 * https://cs.stackexchange.com/a/75271/117308
 **/

require_once __DIR__ . '/../lib/Core.php';

const NUM_ITERATIONS = 100;
const THRESHOLD = 0.01; // discard fractions below this value and renormalize

LocaleUtil::change('en_US.utf8');

// Build a map such that $sinkIds[k] = true iff Entity ID k is a sink.
$sinks = Model::factory('Entity')
  ->table_alias('e')
  ->select('e.*')
  ->join('entity_type', [ 'e.entityTypeId', '=', 'et.id' ],  'et')
  ->where('e.status', Ct::STATUS_ACTIVE)
  ->where('et.loyaltySink', true)
  ->find_many();
$sinkIds = Util::objectProperty($sinks, 'id');

// Load all relations that have weights and convert them to (from, to, weight) edges.
// Map these by from. The result is an array of $from => [ [ $to, $weight ], ... ].
$relations = Model::factory('Relation')
  ->table_alias('r')
  ->select('r.*')
  ->select('rt.weight')
  ->join('entity', ['r.fromEntityId', '=', 'f.id'], 'f')
  ->join('entity', ['r.toEntityId', '=', 't.id'], 't')
  ->join('relation_type', [ 'r.relationTypeId', '=', 'rt.id' ],  'rt')
  ->where('f.status', Ct::STATUS_ACTIVE)
  ->where('t.status', Ct::STATUS_ACTIVE)
  ->where_gt('rt.weight', 0)
  ->find_many();

// first store relations as [ $from => [ $to => $weight ]];
$forwardMap = [];
foreach ($relations as $r) {
  $w = $r->getTemporalWeight() * $r->weight;
  addEdge($forwardMap, $r->fromEntityId, $r->toEntityId, $w);
}

// normalize weights so the sum of outgoing weights is 1
normalizeMap($forwardMap);

// flip the map to get the weights map by toEntityId
$backMap = flip($forwardMap);

// make sinks absorbing
foreach ($sinkIds as $id) {
  $backMap[$id][$id] = 1.0;
}

$sources = Model::factory('Entity')
  ->table_alias('e')
  ->select('e.*')
  ->join('entity_type', [ 'e.entityTypeId', '=', 'et.id' ],  'et')
  ->where('e.status', Ct::STATUS_ACTIVE)
  ->where('et.loyaltySource', true)
  ->find_many();
foreach ($sources as $e) {
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
