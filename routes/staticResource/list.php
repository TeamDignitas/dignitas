<?php

User::enforceModerator();

Smart::assign('staticResources', StaticResource::loadAll());
Smart::display('staticResource/list.tpl');
