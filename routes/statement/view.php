<?php

$id = Request::get('id');
$answerId = Request::get('answerId'); // answer to be highlighted
$postAnswerButton = Request::has('postAnswerButton');
$deleteAnswerId = Request::get('deleteAnswerId');

$statement = Statement::get_by_id($id);
if (!$statement) {
  FlashMessage::add(_('The statement you are looking for does not exist.'));
  Util::redirectToHome();
}

if ($deleteAnswerId) {
  $answer = Answer::get_by_id($deleteAnswerId);
  if (!$answer) {
    FlashMessage::add(_('No such answer exists.'));
  } else if ($answer->statementId != $statement->id) {
    FlashMessage::add(_('The answer does not belong to this statement.'));
  } else if (!$answer->isDeletable()) {
    FlashMessage::add(_('You cannot delete this answer.'));
  } else {

    $answer->delete();
    FlashMessage::add(_('Answer deleted.'), 'success');
    Util::redirectToSelf();

  }
}

if ($postAnswerButton) {
  User::enforce(User::PRIV_ADD_ANSWER);
  $answer = Model::factory('Answer')->create();
  $answer->contents = Request::get('contents');
  $answer->statementId = $statement->id;
  $answer->userId = User::getActiveId();
  $answer->sanitize();

  $errors = validate($answer);
  if (empty($errors)) {
    $answer->save();

    FlashMessage::add(_('Answer posted.'), 'success');
    Util::redirect(sprintf('%s/%s/%s',
                           Router::link('statement/view'),
                           $statement->id,
                           $answer->id));
  } else {
    Smart::assign('errors', $errors);
    Smart::assign('answer', $answer);
  }
} else {
  // first time loading the page
}

try {
  User::checkFlag(Flag::TYPE_STATEMENT, $statement->id);
  $showFlagBox = true;
} catch (Exception $e) {
  $showFlagBox = $statement->isFlagged();
}

Smart::addResources('imageModal', 'simplemde');
Smart::assign([
  'statement' => $statement,
  'entity' => $statement->getEntity(),
  'answers' => $statement->getAnswers(),
  'sources' => $statement->getSources(),
  'showFlagBox' => $showFlagBox,
  'answerId' => $answerId,
]);
Smart::display('statement/view.tpl');

/*************************************************************************/

function validate($answer) {
  $errors = [];

  if (!$answer->contents) {
    $errors['contents'][] = _('Cannot post an empty answer.');
  }

  return $errors;
}
