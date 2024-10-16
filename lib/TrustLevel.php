<?php

/**
 * Code related to an entity's trust level.
 */
class TrustLevel {
  const UNDEFINED = -1;
  const MIN_STATEMENTS_NEEDED = 6;

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

  static function getForEntity(Entity $e): float {
    $statements = Model::factory('Statement')
      ->select('verdict')
      ->where('status', Ct::STATUS_ACTIVE)
      ->where('entityId', $e->id)
      ->where_in('verdict', array_keys(self::COEFS))
      ->find_array();

    if (count($statements) < self::MIN_STATEMENTS_NEEDED) {
      return self::UNDEFINED;
    }

    $sum = 0;
    foreach ($statements as $s) {
      $sum += self::COEFS[$s['verdict']];
    }

    $avg = $sum / count($statements);
    return $avg;
  }

  static function getClass(float $val): string {
    if ($val >= 0.75) {
      return '75';
    } else if ($val >= 0.50) {
      return '50';
    } else if ($val >= 0.25) {
      return '25';
    } else {
      return '0';
    }
  }

  static function getMessage(float $val): string {
    if ($val >= 0.75) {
      return _('label-above-75');
    } else if ($val >= 0.50) {
      return _('label-above-50');
    } else if ($val >= 0.25) {
      return _('label-below-50');
    } else {
      return _('label-below-25');
    }
  }
}
