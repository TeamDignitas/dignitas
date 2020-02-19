<?php

Smart::assign('domains', Domain::loadAll());
Smart::display('domain/list.tpl');
