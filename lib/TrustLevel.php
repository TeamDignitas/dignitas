<?php

/**
 * Code related to an entity's trust level.
 */
class TrustLevel {
  private const MIN_STATEMENTS_NEEDED = 6;

  // Everything not listed below does not count towards MIN_STATEMENTS_NEEDED
  // and does not contribute to the average.
  private const COEFS = [
    Statement::VERDICT_FALSE => 0.00,
    Statement::VERDICT_MOSTLY_FALSE => 0.25,
    Statement::VERDICT_MIXED => 0.50,
    Statement::VERDICT_MOSTLY_TRUE => 0.75,
    Statement::VERDICT_TRUE => 1.00,

    Statement::VERDICT_FLOP => 0.00,
    Statement::VERDICT_HALF_FLOP => 0.50,
    Statement::VERDICT_NO_FLOP => 1.00,

    Statement::VERDICT_PROMISE_BROKEN => 0.00,
    Statement::VERDICT_PROMISE_PARTIAL => 0.50,
    Statement::VERDICT_PROMISE_KEPT_LATE => 0.75,
    // Design decision: ignore stalled promises
    Statement::VERDICT_PROMISE_KEPT => 1.00,
  ];

  public float $value;
  public int $lastTimestamp;

  function __construct(float $value, int $lastTimestamp) {
    $this->value = $value;
    $this->lastTimestamp = $lastTimestamp;
  }

  static function getForEntity(Entity $e): ?TrustLevel {
    $entityIds = $e->getIdAndMemberIds();

    $statements = Model::factory('Statement')
      ->select('verdict')
      ->select('modDate')
      ->where('status', Ct::STATUS_ACTIVE)
      ->where_in('entityId', $entityIds)
      ->where_in('verdict', array_keys(self::COEFS))
      ->order_by_asc('modDate')
      ->find_array();

    if (count($statements) < self::MIN_STATEMENTS_NEEDED) {
      return null;
    }

    $sum = 0;
    $ts = 0;
    foreach ($statements as $s) {
      $sum += self::COEFS[$s['verdict']];
      $ts = $s['modDate'];
    }

    $avg = $sum / count($statements);
    return new TrustLevel($avg, $ts);
  }

  function getClass(): string {
    if ($this->value >= 0.75) {
      return '75';
    } else if ($this->value >= 0.50) {
      return '50';
    } else if ($this->value >= 0.25) {
      return '25';
    } else {
      return '0';
    }
  }

  function getMessage(): string {
    if ($this->value >= 0.75) {
      return _('label-above-75');
    } else if ($this->value >= 0.50) {
      return _('label-above-50');
    } else if ($this->value >= 0.25) {
      return _('label-below-50');
    } else {
      return _('label-below-25');
    }
  }
}
