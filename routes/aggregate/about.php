<?php

$staticResources = StaticResource::addCustomSections('about');

Smart::assign([
  'staticResource' => $staticResources[0],
]);
Smart::display('aggregate/about.tpl');
