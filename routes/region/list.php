<?php

$saveButton = Request::has('saveButton');

$maxDepth = Region::getMaxDepth();

// save nomenclature
if ($saveButton) {
  $names = Request::getArray('name');

  $i = 0;
  for ($depth = 0; $depth <= $maxDepth; $depth++) {
    foreach (Config::LOCALES as $locale => $localeName) {
      $varName = Region::getVariableName($depth, $locale);
      $varValue = $names[$i++];
      if ($varValue) {
        Variable::poke($varName, $varValue);
      } else {
        Variable::deleteByName($varName);
      }
    }
  }

  Snackbar::add(_('info-region-nomenclature-saved'), 'success');
  Util::redirectToSelf();
}

// load nomenclature
$nomenclature = [];
for ($depth = 0; $depth <= $maxDepth; $depth++) {
  foreach (Config::LOCALES as $locale => $localeName) {
    $varName = Region::getVariableName($depth, $locale);
    $nomenclature[$depth][$locale] = Variable::peek($varName, '');
  }
}

Smart::assign([
  'regions' => Region::loadTree(),
  'nomenclature' => $nomenclature,
  'maxDepth' => $maxDepth,
]);
Smart::display('region/list.tpl');
