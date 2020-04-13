<?php

User::enforceModerator();

Smart::assign('entityTypes', EntityType::loadAll());
Smart::display('entityType/list.tpl');
