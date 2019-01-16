<?php

require_once '../lib/Core.php';

$id = Request::get('id');
LocaleUtil::change($id);

FlashMessage::add(
  _('Interface language changed. Page contents are always in Romanian.'),
  'success');
Util::redirect(Core::getWwwRoot());
