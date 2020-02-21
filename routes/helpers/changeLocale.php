<?php

$id = Request::get('id');
LocaleUtil::change($id);

FlashMessage::add(
  _('info-language-changed'),
  'success');
Util::redirectToHome();
