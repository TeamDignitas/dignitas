<?php

User::enforceModerator();

$id = Request::get('id');
$saveButton = Request::has('saveButton');
$deleteButton = Request::has('deleteButton');

if ($id) {
  $cr = CannedResponse::get_by_id($id);
} else {
  $cr = Model::factory('CannedResponse')->create();
  $cr->rank = 1 + Model::factory('CannedResponse')->count();
}

if ($deleteButton) {
  FlashMessage::add(_('info-canned-response-deleted'), 'success');
  $cr->delete();
  Util::redirectToRoute('cannedResponse/list');
}

if ($saveButton) {
  $cr->contents = Request::get('contents');

  $errors = validate($cr);
  if (empty($errors)) {
    $cr->save();
    FlashMessage::add(_('info-canned-response-saved'), 'success');
    Util::redirect(Router::link('cannedResponse/list'));
  } else {
    Smart::assign('errors', $errors);
  }
}

Smart::assign([
  'cannedResponse' => $cr,
  'charsRemaining' => Comment::MAX_LENGTH - mb_strlen($cr->contents),
]);
Smart::addResources('easymde');
Smart::display('cannedResponse/edit.tpl');

/*************************************************************************/

function validate($cr) {
  $errors = [];

  if (!$cr->contents) {
    $errors['contents'][] = _('info-must-enter-canned-response-contents');
  }

  if (mb_strlen($cr->contents) > Comment::MAX_LENGTH) {
    $errors['contents'][] =
      sprintf(_('info-canned-response-length-limit-%d'), Comment::MAX_LENGTH);
  }

  return $errors;
}
