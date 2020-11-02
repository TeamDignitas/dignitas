<?php

$staticResources = StaticResource::addCustomSections('contact');

Smart::assign([
  'staticResource' => $staticResources[0],
]);
Smart::display('aggregate/contact.tpl');
