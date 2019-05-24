<?php

Smart::assign('tags', Tag::loadTree());
Smart::display('tag/list.tpl');
