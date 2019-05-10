<?php

User::enforce(1); /* just ensure user is logged in */

Smart::display('aggregate/dashboard.tpl');
