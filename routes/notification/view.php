<?php

/**
 * Display page #p (1-based) of the user's notifications.
 **/

if (!User::getActive()) {
  Util::redirectToLogin(); // just ensure user is logged in
}

$p = Request::get('p', 1);

$notifications = Notification::getPage($p);

Smart::assign([
  'notifications' => $notifications,
  'numPages' => Notification::getNumPages(),
]);
Smart::addResources('pagination');
Smart::display('notification/view.tpl');
