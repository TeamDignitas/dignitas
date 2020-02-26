<?php

Smart::assign([
  'map' => Statement::getStatementsWithBadVerdicts(),
]);

Smart::display('statement/verdictReport.tpl');
