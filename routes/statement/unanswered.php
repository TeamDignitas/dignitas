<?php

$verdicts = [ Statement::VERDICT_NONE ];

list($numStatementPages, $statements) =
  Search::searchStatements(
    [ 'verdicts' => $verdicts ],
    Ct::SORT_CREATE_DATE_DESC, // SORT_VERDICT_DATE_DESC is no use here
    1);

Smart::assign([
  'numStatementPages' => $numStatementPages,
  'statements' => $statements,
  'verdicts' => $verdicts,
]);
Smart::addResources('pagination', 'datepicker', 'bootstrap-select');
Smart::display('statement/unanswered.tpl');
