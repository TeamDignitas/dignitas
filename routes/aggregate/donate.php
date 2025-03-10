<?php

$staticResources = StaticResource::addCustomSections('donate');

Smart::assign([
  'staticResource' => $staticResources[0],
]);

Smart::display('aggregate/donate.tpl');
