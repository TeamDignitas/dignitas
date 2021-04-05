<?php

User::enforceModerator();

Smart::assign([
  'relationTypes' => RelationType::loadAll(),
  'numEntityTypes' => count(EntityType::loadAll()),
]);
Smart::display('relationType/list.tpl');
