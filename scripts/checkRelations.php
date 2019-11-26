<?php
/**
 * This script goes through the entity relations and reports missing
 * reciprocal relations. For example, if A is a close relative of B, then B
 * should also be a close relative of A.
 **/

require_once __DIR__ . '/../lib/Core.php';

LocaleUtil::change('en_US.utf8');

$relations = Model::factory('Relation')
  ->where_in('type', [ Relation::TYPE_CLOSE_RELATIVE, Relation::TYPE_DISTANT_RELATIVE])
  ->find_many();

$numMismatches = 0;

foreach ($relations as $r) {
  // look for an exact match
  $reciprocals = Model::factory('Relation')
    ->where('fromEntityId', $r->toEntityId)
    ->where('toEntityId', $r->fromEntityId)
    ->where('type', $r->type)
    ->where('startDate', $r->startDate)
    ->where('endDate', $r->endDate)
    ->find_many();
  if (count($reciprocals) != 1) {
    printf("[%s] should be a [%s] [%s] from [%s] to [%s] (there are %d such relations)\n",
           $r->getToEntity(),
           $r->getTypeName(),
           $r->getFromEntity(),
           Time::localDate($r->startDate),
           Time::localDate($r->endDate),
           count($reciprocals));
    $numMismatches++;
  }

}

print "{$numMismatches} mismatches found.\n";
