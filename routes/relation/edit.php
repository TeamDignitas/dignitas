<?php

$id = Request::get('id');
$saveButton = Request::has('saveButton');

$relation = Relation::get_by_id($id);
if (!$relation) {
  Snackbar::add(_('info-no-such-relation'));
  Util::redirectToHome();
}

$fromEntity = Entity::get_by_id($relation->fromEntityId);
if (!$fromEntity || !$fromEntity->isEditable()) {
  Snackbar::add(_('info-cannot-edit-relations'));
  Util::redirect(Router::link('entity/view') . '/' . $fromEntity->id);
}

if ($saveButton) {
  $links = Link::build(
    Request::getArray('linkIds'),
    Request::getArray('linkUrls'));

  $errors = validate($links);
  if (empty($errors)) {
    Link::update($relation, $links);
    Snackbar::add(_('info-relation-updated'), 'success');
    Util::redirect(Router::link('entity/view') . '/' . $fromEntity->id);
  } else {
    Smart::assign([
      'errors' => $errors,
      'links' => $links,
    ]);
  }
} else {
  // first time loading the page
  Smart::assign([
    'links' => $relation->getLinks(),
  ]);
}

Smart::addResources('linkEditor');
Smart::assign([
  'relation' => $relation,
  'fromEntity' => $fromEntity,
]);
Smart::display('relation/edit.tpl');

/*************************************************************************/

function validate($links) {
  $errors = [];

  $countBadUrls = 0;
  foreach ($links as $l) {
    if (!$l->validUrl()) {
      $countBadUrls++;
    }
  }
  if ($countBadUrls) {
    $errors['links'][] = _('info-invalid-relation-links');
  }

  return $errors;
}
