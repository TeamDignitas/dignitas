<?php

$id = Request::get('id');
LocaleUtil::change($id);

Snackbar::add(
  _('info-language-changed'),
  'success');
Util::redirectToHome();
