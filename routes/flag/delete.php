<?php

$objectType = Request::get('objectType');
$objectId = Request::get('objectId');

$userId = User::getActiveId();

// Skip error checking. If the user somehow flagged the object, the user
// should be able to unflag.
Flag::delete_all_by_userId_objectType_objectId($userId, $objectType, $objectId);

header('Content-Type: application/json');
print json_encode(_('Your flag was deleted.'));
