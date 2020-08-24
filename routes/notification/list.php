<?php

/**
 * Display page #p (1-based) of the user's notifications as JSON.
 *
 * For illegal operations, we return an error code of 404 and print the
 * JSON-encoded error message.
 **/

$p = Request::get('p', 1);

header('Content-Type: application/json');

try {

  if (!User::getActive()) {
    throw new Exception(_('info-must-log-in'));
  }

  $notifications = Notification::getPage($p);

  Smart::assign('notifications', $notifications);
  $html = Smart::fetch('bits/notifications.tpl');
  print json_encode($html);

} catch (Exception $e) {

  http_response_code(404);
  print json_encode($e->getMessage());

}
