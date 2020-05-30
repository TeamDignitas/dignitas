<?php

/**
 * Displays a pagination box with $n pages where the $k-th page is active.
 */

$n = Request::get('n');
$k = Request::get('k');

Smart::assign([
  'n' => $n,
  'k' => $k,
]);
$html = Smart::fetch('bits/pagination.tpl');
print $html;
