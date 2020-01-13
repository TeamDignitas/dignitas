<?php

$id = Request::get('id');

$answer = Answer::get_by_id($id);
if (!$answer) {
  FlashMessage::add(_('The answer you are looking for does not exist.'));
  Util::redirectToHome();
}

if (!$answer->isViewable()) {
  FlashMessage::add(_('This answer was deleted and is only visible to privileged users.'));
  Util::redirectToHome();
}

$title = _('Answer history for') . ' #' . $answer->id;

Smart::assign([
  'history' => ObjectDiff::getRevisions($answer),
  'answer' => $answer,
  'title' => $title,
]);

Smart::addResources('history');
Smart::display('bits/history.tpl');
