<?php

$id = Request::get('id');

$answer = Answer::get_by_id($id);
if (!$answer) {
  Snackbar::add(_('info-answer-does-not-exist'));
  Util::redirectToHome();
}

if (!$answer->isViewable()) {
  Snackbar::add(_('info-answer-restricted'));
  Util::redirectToHome();
}

$title = _('title-answer-history') . ' #' . $answer->id;

Smart::assign([
  'history' => ObjectDiff::loadFor($answer),
  'title' => $title,
  'backButtonText' => _('label-back-to-answer'),
  'backButtonUrl' => $answer->getViewUrl(),
]);

Smart::addResources('history');
Smart::display('bits/history.tpl');
