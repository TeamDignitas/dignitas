<?php

$id = Request::get('id');
$answerId = Request::get('answerId'); // answer to be highlighted
$postAnswerButton = Request::has('postAnswerButton');

$statement = Statement::get_by_id($id);
if (!$statement) {
  FlashMessage::add(_('The statement you are looking for does not exist.'));
  Util::redirectToHome();
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

Smart::addResources('simplemde');
Smart::assign([
  'statement' => $statement,
  'entity' => $statement->getEntity(),
  'answers' => $statement->getAnswers(),
  'sources' => $statement->getSources(),
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
