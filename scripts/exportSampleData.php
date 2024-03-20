#!/usr/bin/php
<?php
/**
 * This script exports a minimal usable dataset. Its counterpart will be able
 * to import it. This helps bootstrap a development installation.
 *
 * Limitations:
 * - no revision data.
 **/

ini_set('memory_limit','1G');

require_once __DIR__ . '/../lib/Core.php';

LocaleUtil::change('en_US.utf8');

$data = [];

// Grab some tables entirely.
const WHOLE_TABLES = [
  'CannedResponse', 'Domain', 'EntityType', 'HelpCategory', 'HelpCategoryT',
  'HelpPage', 'HelpPageT', 'Region', 'RelationType', 'StaticResource', 'Tag',
  'Variable',
];
foreach (WHOLE_TABLES as $table) {
  $data[$table] = Model::factory($table)->find_array();
}
// we will need these later to retrieve files from the shared drive
$domains = $data['Domain'];
$staticResources = $data['StaticResource'];

// Start from statements. We will filter these when it becomes necessary.
// Don't allow duplicates and pending edits so that it doesn't mushroom.
$statements = Model::factory('Statement')
  ->where('duplicateId', 0)
  ->where('pendingEditId', 0)
  ->where_not_equal('status', Ct::STATUS_PENDING_EDIT)
  ->order_by_desc('createDate')
  ->limit(50)
  ->find_array();
$statementIds = array_column($statements, 'id');

$data['Statement'] = $statements;
$data['StatementExt'] = Model::factory('StatementExt')
  ->where_in('statementId', $statementIds)
  ->find_array();

// Load answers for the selected statements.
$answers = Model::factory('Answer')
  ->where_in('statementId', $statementIds)
  ->where('pendingEditId', 0)
  ->where_not_equal('status', Ct::STATUS_PENDING_EDIT)
  ->find_array();
$answerIds = array_column($answers, 'id');
$data['Answer'] = $answers;
$data['AnswerExt'] = Model::factory('AnswerExt')
  ->where_in('answerId', $answerIds)
  ->find_array();

// Load comments for the selected statements and answers.
$comments = getComments($statementIds, $answerIds);
$commentIds = array_column($comments, 'id') ?: [ 0 ];
$data['Comment'] = $comments;
$data['CommentExt'] = Model::factory('CommentExt')
  ->where_in('commentId', $commentIds)
  ->find_array();

// Load entities. Their duplicates, if any, are correctly loaded.
$entities = getEntities($statements);
$entityIds = array_column($entities, 'id');
$data['Entity'] = $entities;

// Load entity-related data
$data['Alias'] = Model::factory('Alias')
  ->where_in('entityId', $entityIds)
  ->find_array();
// Since the $entityIds set is guaranteed to be closed under relations and
// loyalties, it suffices to load these by either endpoint (not both).
$data['Loyalty'] = Model::factory('Loyalty')
  ->where_in('fromEntityId', $entityIds)
  ->find_array();
$relations = Model::factory('Relation')
  ->where_in('fromEntityId', $entityIds)
  ->find_array();
$relationIds = array_column($relations, 'id');
$data['Relation'] = $relations;

$data['ObjectTag'] = getObjectTags($statementIds, $answerIds, $entityIds);
$data['Link'] = getLinks($statementIds, $entityIds, $relationIds);

$reviews = getReviews($statementIds, $answerIds, $commentIds, $entityIds);
$reviewIds = array_column($reviews, 'id');
$data['Review'] = $reviews;

$flags = Model::factory('Flag')
  ->where_in('reviewId', $reviewIds)
  ->find_array();
$data['Flag'] = $flags;

$votes = getVotes($statementIds, $answerIds, $commentIds);
$data['Vote'] = $votes;

// Load all users involved with the objects above.
$userIds = getUserIds($statements, $answers, $comments, $entities, $flags, $votes);
$users = Model::factory('User')
  ->where_in('id', $userIds)
  ->find_array();
foreach ($users as &$u) {
  $u['email'] = $u['nickname']; // hide email addresses
}
$data['User'] = $users;
$data['UserExt'] = Model::factory('UserExt')
  ->where_in('userId', $userIds)
  ->find_array();

$refs = getAttachmentReferences($statementIds, $answerIds, $entityIds, $userIds);
$data['AttachmentReference'] = $refs;

$attachmentIds = array_column($refs, 'attachmentId') ?: [ 0 ];
$attachments = Model::factory('Attachment')
  ->where_in('id', $attachmentIds)
  ->find_array();
$data['Attachment'] = $attachments;

$files = [];
getUploads($attachments, 'Attachment', $files);
getUploads($domains, 'Domain', $files);
getUploads($entities, 'Entity', $files);
getUploads($users, 'User', $files);
getStaticResources($staticResources, $files);

$result = [
  'objects' => $data,
  'files' => $files,
];

print json_encode($result, JSON_PRETTY_PRINT);

/*************************************************************************/

// Load entities to export. Start from the statements' authors, then
// repeatedly include their duplicate targets and their relations until the
// list stabilizes.
function getEntities(&$statements) {
  $entityIds = array_unique(array_column($statements, 'entityId'));
  do {
    $startCount = count($entityIds);
    $entities = Model::factory('Entity')
      ->where_in('id', $entityIds)
      ->find_array();
    $relations = array_merge(
      Model::factory('Relation')
      ->where_in('fromEntityId', $entityIds)
      ->find_array(),
      Model::factory('Relation')
      ->where_in('toEntityId', $entityIds)
      ->find_array());
    $entityIds = array_unique(array_merge(
      $entityIds,
      array_column($entities, 'duplicateId'),
      array_column($relations, 'fromEntityId'),
      array_column($relations, 'toEntityId')));
    $endCount = count($entityIds);
  } while ($endCount > $startCount);
  return $entities;
}

function getUserIds(&$statements, &$answers, &$comments, &$entities, &$flags, &$votes) {
  return array_unique(array_merge(
    array_column($statements, 'userId'),
    array_column($statements, 'statusUserId'),
    array_column($answers, 'userId'),
    array_column($answers, 'statusUserId'),
    array_column($comments, 'userId'),
    array_column($comments, 'statusUserId'),
    array_column($entities, 'userId'),
    array_column($entities, 'statusUserId'),
    array_column($flags, 'userId'),
    array_column($votes, 'userId')
  ));
}

function getComments($statementIds, $answerIds) {
  $l1 = Model::factory('Comment')
    ->where('objectType', Proto::TYPE_STATEMENT)
    ->where_in('objectId', $statementIds)
    ->find_array();
  $l2 = Model::factory('Comment')
    ->where('objectType', Proto::TYPE_ANSWER)
    ->where_in('objectId', $answerIds)
    ->find_array();
  return array_merge($l1, $l2);
}

function getObjectTags($statementIds, $answerIds, $entityIds) {
  $l1 = Model::factory('ObjectTag')
    ->where('objectType', Proto::TYPE_STATEMENT)
    ->where_in('objectId', $statementIds)
    ->find_array();
  $l2 = Model::factory('ObjectTag')
    ->where('objectType', Proto::TYPE_ANSWER)
    ->where_in('objectId', $answerIds)
    ->find_array();
  $l3 = Model::factory('ObjectTag')
    ->where('objectType', Proto::TYPE_ENTITY)
    ->where_in('objectId', $entityIds)
    ->find_array();
  return array_merge($l1, $l2, $l3);
}

function getLinks($statementIds, $entityIds, $relationIds) {
  $l1 = Model::factory('Link')
    ->where('objectType', Proto::TYPE_STATEMENT)
    ->where_in('objectId', $statementIds)
    ->find_array();
  $l2 = Model::factory('Link')
    ->where('objectType', Proto::TYPE_ENTITY)
    ->where_in('objectId', $entityIds)
    ->find_array();
  $l3 = Model::factory('Link')
    ->where('objectType', Proto::TYPE_RELATION)
    ->where_in('objectId', $relationIds)
    ->find_array();
  return array_merge($l1, $l2, $l3);
}

function getReviews($statementIds, $answerIds, $commentIds, $entityIds) {
  $l1 = Model::factory('Review')
    ->where('objectType', Proto::TYPE_STATEMENT)
    ->where_in('objectId', $statementIds)
    ->find_array();
  $l2 = Model::factory('Review')
    ->where('objectType', Proto::TYPE_ANSWER)
    ->where_in('objectId', $answerIds)
    ->find_array();
  $l3 = Model::factory('Review')
    ->where('objectType', Proto::TYPE_COMMENT)
    ->where_in('objectId', $commentIds)
    ->find_array();
  $l4 = Model::factory('Review')
    ->where('objectType', Proto::TYPE_ENTITY)
    ->where_in('objectId', $entityIds)
    ->find_array();
  return array_merge($l1, $l2, $l3, $l4);
}

function getVotes($statementIds, $answerIds, $commentIds) {
  $l1 = Model::factory('Vote')
    ->where('objectType', Proto::TYPE_STATEMENT)
    ->where_in('objectId', $statementIds)
    ->find_array();
  $l2 = Model::factory('Vote')
    ->where('objectType', Proto::TYPE_ANSWER)
    ->where_in('objectId', $answerIds)
    ->find_array();
  $l3 = Model::factory('Vote')
    ->where('objectType', Proto::TYPE_COMMENT)
    ->where_in('objectId', $commentIds)
    ->find_array();
  return array_merge($l1, $l2, $l3);
}

function getAttachmentReferences($statementIds, $answerIds, $entityIds, $userIds) {
  $l1 = Model::factory('AttachmentReference')
    ->where('objectType', Proto::TYPE_STATEMENT)
    ->where_in('objectId', $statementIds)
    ->find_array();
  $l2 = Model::factory('AttachmentReference')
    ->where('objectType', Proto::TYPE_ANSWER)
    ->where_in('objectId', $answerIds)
    ->find_array();
  $l3 = Model::factory('AttachmentReference')
    ->where('objectType', Proto::TYPE_ENTITY)
    ->where_in('objectId', $entityIds)
    ->find_array();
  $l4 = Model::factory('AttachmentReference')
    ->where('objectType', Proto::TYPE_USER)
    ->where_in('objectId', $userIds)
    ->find_array();
  return array_merge($l1, $l2, $l3, $l4);
}

function getUploads($objects, $class, &$result) {
  foreach ($objects as $rec) {
    if ($rec['fileExtension']) {
      $id = $rec['id'];
      $obj = Model::factory($class)->where('id', $id)->find_one();
      $file = $obj->getFileLocation($class::$FULL_GEOMETRY);
      $result[$class][$id] = base64_encode(file_get_contents($file));
    }
  }
}

function getStaticResources($objects, &$result) {
  foreach ($objects as $rec) {
    $id = $rec['id'];
    $obj = StaticResource::get_by_id($id);
    $file = $obj->getFilePath();
    $result['StaticResource'][$id] = base64_encode(file_get_contents($file));
  }
}
