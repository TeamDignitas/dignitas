#!/usr/bin/php
<?php
/**
 * This script checks that entities that need long/short possessive forms have
 * them defined.
 **/

require_once __DIR__ . '/../lib/Core.php';

LocaleUtil::change('en_US.utf8');

reportField('longPossessive', RelationType::PHRASE_LONG_POSSESSIVE);
reportField('shortPossessive', RelationType::PHRASE_SHORT_POSSESSIVE);

/*************************************************************************/

function reportField($field, $phrase) {
  $entities = Model::factory('Entity')
    ->table_alias('e')
    ->select('e.*')
    ->distinct()
    ->join('relation', [ 'e.id', '=', 'r.toEntityId'], 'r')
    ->join('relation_type', [ 'r.relationTypeId', '=', 'rt.id' ],  'rt')
    ->where('rt.phrase', $phrase)
    ->where_not_like("e.{$field}", '%[%]%')
    ->find_many();

  foreach ($entities as $e) {
    $val = $e->$field;
    if (!$val) {
      printf("Entity %s (ID #%d) needs a non-empty value for field %s.\n",
              $e, $e->id, $field);
    } else {
      printf("Entity %s (ID #%d) has no hyperlink in value «%s» of field %s.\n",
             $e, $e->id, $val, $field);
    }
  }
}
