<?php

const VIEWABLE_TYPES = [
  Proto::TYPE_STATEMENT,
  Proto::TYPE_ANSWER,
  Proto::TYPE_ENTITY,
  Proto::TYPE_COMMENT,
];

$objectType = Request::get('objectType');
$objectId = Request::get('objectId');

if (!in_array($objectType, VIEWABLE_TYPES)) {
  Snackbar::add(_('info-view-markdown-bad-object-type'));
  Util::redirectToHome();
}

$obj = Proto::getObjectByTypeId($objectType, $objectId);
if (!$obj) {
  Snackbar::add(_('info-view-markdown-no-object'));
  Util::redirectToHome();
}

if (!$obj->isViewable()) {
  Snackbar::add(_('info-view-markdown-restricted-object'));
  Util::redirectToHome();
}

Smart::addResources('easymde');
Smart::assign('obj', $obj);
Smart::display('aggregate/viewMarkdown.tpl');
