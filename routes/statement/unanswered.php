<?php

$verdicts = [ Ct::VERDICT_NONE ];

list($numStatementPages, $statements) =
  Search::searchStatements(
    [ 'verdicts' => $verdicts ],
    Ct::SORT_CREATE_DATE_DESC,
    1);

Smart::assign([
  'numStatementPages' => $numStatementPages,
  'statements' => $statements,
  'verdicts' => $verdicts,
]);
Smart::addResources('pagination', 'datepicker', 'bootstrap-select');
Smart::display('statement/unanswered.tpl');
