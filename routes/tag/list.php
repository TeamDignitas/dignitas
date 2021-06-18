<?php

// not a real tag, but serves as a parent for tags without a parent
$root = Model::factory('Tag')->create();
$root->loadSubtree();

Smart::assign('root', $root);
Smart::display('tag/list.tpl');
