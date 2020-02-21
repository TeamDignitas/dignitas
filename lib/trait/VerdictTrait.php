<?php

/**
 * Method implementations for objects that have verdicts (statements and
 * answers).
 */
trait VerdictTrait {

  static function verdictName($verdict) {
    switch ($verdict) {
      case Ct::VERDICT_NONE:         return _('verdict-none');
      case Ct::VERDICT_UNDECIDABLE:  return _('verdict-undecidable');
      case Ct::VERDICT_FALSE:        return _('verdict-false');
      case Ct::VERDICT_MOSTLY_FALSE: return _('verdict-mostly-false');
      case Ct::VERDICT_MIXED:        return _('verdict-mixed');
      case Ct::VERDICT_MOSTLY_TRUE:  return _('verdict-mostly-true');
      case Ct::VERDICT_TRUE:         return _('verdict-true');
    }
  }

  function getVerdictName() {
    return self::verdictName($this->verdict);
  }

}
