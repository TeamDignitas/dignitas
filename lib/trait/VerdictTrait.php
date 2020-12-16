<?php

/**
 * Method implementations for objects that have verdicts (statements and
 * answers).
 */
trait VerdictTrait {

  static function verdictName($verdict) {
    switch ($verdict) {
      case Statement::VERDICT_NONE:              return _('verdict-none');
      case Statement::VERDICT_UNDECIDABLE:       return _('verdict-undecidable');
      case Statement::VERDICT_FALSE:             return _('verdict-false');
      case Statement::VERDICT_MOSTLY_FALSE:      return _('verdict-mostly-false');
      case Statement::VERDICT_MIXED:             return _('verdict-mixed');
      case Statement::VERDICT_MOSTLY_TRUE:       return _('verdict-mostly-true');
      case Statement::VERDICT_TRUE:              return _('verdict-true');
      case Statement::VERDICT_FLOP:              return _('verdict-flop');
      case Statement::VERDICT_HALF_FLOP:         return _('verdict-half-flop');
      case Statement::VERDICT_NO_FLOP:           return _('verdict-no-flop');
      case Statement::VERDICT_PROMISE_BROKEN:    return _('verdict-promise-broken');
      case Statement::VERDICT_PROMISE_STALLED:   return _('verdict-promise-stalled');
      case Statement::VERDICT_PROMISE_PARTIAL:   return _('verdict-promise-partial');
      case Statement::VERDICT_PROMISE_KEPT_LATE: return _('verdict-promise-kept-late');
      case Statement::VERDICT_PROMISE_KEPT:      return _('verdict-promise-kept');
    }
  }

  function getVerdictName() {
    return self::verdictName($this->verdict);
  }

}
