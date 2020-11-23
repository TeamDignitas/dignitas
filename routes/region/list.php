<?php

Smart::assign('regions', Region::loadTree());
Smart::display('region/list.tpl');
