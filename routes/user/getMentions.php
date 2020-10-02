<?php

/**
 * Returns a list of users that participated in a statement's thread.
 **/

$statementId = Request::get('statementId');

$userIds = [ 0 ]; // where_in breaks on empty sets

$statement = Statement::get_by_id($statementId);

if ($statement) {
  $userIds[$statement->userId] = true;
  addCommentUsers($statement, $userIds);

  $answers = Answer::get_all_by_statementId($statementId);
  foreach ($answers as $answer) {
    $userIds[$answer->userId] = true;
    addCommentUsers($answer, $userIds);
  }
}

// resolve user IDs to nicknames
$users = Model::factory('User')
  ->where_in('id', array_keys($userIds))
  ->order_by_asc('nickname')
  ->find_many();

$nicknames = [];
foreach ($users as $u) {
  $nicknames[] = $u->nickname;
}

header('Content-Type: application/json');
print json_encode($nicknames);

/*************************************************************************/

function addCommentUsers($object, &$userIds) {
  $comments = Comment::getFor($object);
  foreach ($comments as $c) {
    $userIds[$c->userId] = true;
  }
}
