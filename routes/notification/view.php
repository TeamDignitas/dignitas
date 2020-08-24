<?php

/**
 * Display page #p (1-based) of the user's notifications.
 **/

if (!User::getActive()) {
  Util::redirectToLogin(); // just ensure user is logged in
}

$p = Request::get('p', 1);

$notifications = Notification::getPage($p);

// Mark all notifications as seen. I'm not sure how to handle the case where
// the user sees the first page of notifications, but other, older
// notifications remain unseen.
Notification::markAllSeen();

Smart::assign([
  'notifications' => $notifications,
  'numPages' => Notification::getNumPages(),
]);
Smart::addResources('pagination');
Smart::display('notification/view.tpl');
