<?php
/**
 * This script goes through entities and relations and reports some
 * inconsistencies.
 **/

require_once __DIR__ . '/../lib/Core.php';

LocaleUtil::change('en_US.utf8');

/**
 * Go through symmetric relations and report missing reciprocal relations.
 * For example, if A is a close relative of B, then B should also be a close
 * relative of A.
 **/
$relations = Model::factory('Relation')
  ->table_alias('r')
  ->select('r.*')
  ->join('relation_type', [ 'r.relationTypeId', '=', 'rt.id' ],  'rt')
  ->where('rt.symmetric', true)
  ->find_many();

$numMismatches = 0;

foreach ($relations as $r) {
  // look for an exact match
  $reciprocals = Model::factory('Relation')
    ->where('fromEntityId', $r->toEntityId)
    ->where('toEntityId', $r->fromEntityId)
    ->where('relationTypeId', $r->relationTypeId)
    ->where('startDate', $r->startDate)
    ->where('endDate', $r->endDate)
    ->find_many();
  if (count($reciprocals) != 1) {
    printf("[%s] should be a [%s] [%s] from [%s] to [%s] (there are %d such relations).\n",
           $r->getToEntity(),
           $r->getRelationType()->name,
           $r->getFromEntity(),
           Time::localDate($r->startDate),
           Time::localDate($r->endDate),
           count($reciprocals));
    $numMismatches++;
  }

}

if ($numMismatches) {
  print "{$numMismatches} symmetry mismatches found.\n";
}

/**
 * Go through entity types and check that loyalty sinks also have colors.
 **/
$ets = EntityType::loadAll();
foreach ($ets as $et) {
  if ($et->loyaltySink && !$et->hasColor) {
    printf("Entity type [%s] is a loyalty sink, so it should support colors.\n", $et->name);
  }
}

/**
 * Go through entity types and report loyalty sinks that have outgoing
 * relations with nonzero weights.
 **/
$ets = Model::factory('EntityType')
  ->table_alias('et')
  ->select('et.*')
  ->select('rt.name', 'relName')
  ->join('relation_type', [ 'et.id', '=', 'rt.fromEntityTypeId' ], 'rt')
  ->where('et.loyaltySink', true)
  ->where_gt('rt.weight', 0)
  ->find_many();
foreach ($ets as $et) {
  printf("Entity type [%s] is a loyalty sink, " .
         "but it has outgoing relation [%s] of nonzero weight.\n",
         $et->name, $et->relName);
}
