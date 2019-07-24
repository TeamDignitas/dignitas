<?php

class Entity extends BaseObject implements DatedObject {
  use UploadTrait;

  const TYPE_PERSON = 1;
  const TYPE_PARTY = 2;
  const TYPE_UNION = 3; // of parties
  const TYPE_WEBSITE = 4;

  const DEFAULT_COLOR = '#ffffff';

  // parties and unions have colors
  const TYPES = [
    self::TYPE_PERSON => [ 'hasColor' => false ],
    self::TYPE_PARTY => [ 'hasColor' => true ],
    self::TYPE_UNION => [ 'hasColor' => true ],
    self::TYPE_WEBSITE => [ 'hasColor' => false ],
  ];

  static function typeName($type) {
    switch ($type) {
      case self::TYPE_PERSON:   return _('person');
      case self::TYPE_PARTY:    return _('party');
      case self::TYPE_UNION:    return _('union');
      case self::TYPE_WEBSITE:  return _('website');
    }
  }

  function getTypeName() {
    return self::typeName($this->type);
  }

  private function getFileSubdirectory() {
    return 'entity';
  }

  private function getFileRoute() {
    return 'entity/image';
  }

  function getColor() {
    return $this->color ? $this->color : self::DEFAULT_COLOR;
  }

  function setColor($color) {
    $this->color = strcasecmp($color, self::DEFAULT_COLOR) ? $color : '';
  }

  function hasColor() {
    return self::TYPES[$this->type]['hasColor'] ?? false;
  }

  function getAliases() {
    return Model::factory('Alias')
      ->where('entityId', $this->id)
      ->order_by_asc('rank')
      ->find_many();
  }

  function getRelations() {
    return Model::factory('Relation')
      ->where('fromEntityId', $this->id)
      ->order_by_asc('rank')
      ->find_many();
  }

  // returns a map of Entity (party) => fraction, where the fractions add up to 1.
  function getLoyalty() {
    // entityId (party ID) => sum of coefficients
    $map = [];

    foreach ($this->getRelations() as $rel) {
      $relStartDays = Time::daysAgo($rel->startDate) ?? 10000;
      $relEndDays = Time::daysAgo($rel->endDate) ?? 0;
      // now intersect this interval with each predefined loyalty interval

      $prevBoundary = 0; // today
      foreach (Config::LOYALTY_INTERVALS as $rec) {
        list ($boundary, $score) = $rec;

        // invariant: $relEndDays < $relStartDays and $prevBoundary < $prevBoundary
        $left = max($relEndDays, $prevBoundary);
        $right = min($relStartDays, $boundary);

        if ($left < $right) {
          $existing = $map[$rel->toEntityId] ?? 0;
          $map[$rel->toEntityId] = $existing + ($right - $left) * $score;
        }

        $prevBoundary = $boundary;
      }
    }

    // normalization
    if (!empty($map)) {
      $sum = array_sum($map);
      foreach ($map as $k => &$value) {
        $value /= $sum;
      }
      arsort($map);
    }

    // load the entities for the given IDs
    $results = [];
    foreach ($map as $entityId => &$value) {
      $results[] = [
        'entity' => Entity::get_by_id($entityId),
        'value' => $value,
      ];
    }

    return $results;
  }

  public function delete() {
    Log::warning("Deleted entity {$this->id} ({$this->name})");
    $this->deleteFiles();
    Alias::delete_all_by_entityId($this->id);
    Statement::delete_all_by_entityId($this->id);
    Relation::delete_all_by_fromEntityId($this->id);
    Relation::delete_all_by_toEntityId($this->id);
    parent::delete();
  }

  public function __toString() {
    return $this->name;
  }

}
