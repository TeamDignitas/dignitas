<?php

$id = Request::get('id');
$saveButton = Request::has('saveButton');

$relation = Relation::get_by_id($id);
if (!$relation) {
  FlashMessage::add(_('Relation does not exist.'));
  Util::redirectToHome();
}

$fromEntity = Entity::get_by_id($relation->fromEntityId);
if (!$fromEntity || !$fromEntity->isEditable()) {
  FlashMessage::add(_("You may not edit this author's relations."));
  Util::redirect(Router::link('entity/view') . '/' . $fromEntity->id);
}

if ($saveButton) {
  $links = Link::build(
    Request::getArray('linkIds'),
    Request::getArray('linkUrls'));

  $errors = validate($links);
  if (empty($errors)) {
    Link::update($relation, $links);
    FlashMessage::add(_('Relation sources updated.'), 'success');
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
    $errors['links'][] = _('Some source URLS are invalid.');
  }

  return $errors;
}
