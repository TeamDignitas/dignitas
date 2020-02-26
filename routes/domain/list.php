<?php

User::enforceModerator();

Smart::assign('domains', Domain::loadAll());
Smart::display('domain/list.tpl');
