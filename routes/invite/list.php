<?php

User::enforceModerator();

if (!Config::ALLOW_INVITES) {
  Snackbar::add(_('info-invites-disabled'), 'warning');
}

Smart::assign('invites', Invite::loadAll());
Smart::display('invite/list.tpl');
