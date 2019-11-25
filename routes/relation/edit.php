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
}

if ($saveButton) {
  $sources = buildSources(
    $relation,
    Request::getArray('ssIds'),
    Request::getArray('ssUrls'));

  $errors = validate($sources);
  if (empty($errors)) {
    RelationSource::updateDependants($sources, 'relationId', $relation->id, 'rank');
    FlashMessage::add(_('Relation sources updated.'), 'success');
    Util::redirect(Router::link('entity/view') . '/' . $fromEntity->id);
  } else {
    Smart::assign([
      'errors' => $errors,
      'sources' => $sources,
    ]);
  }
} else {
  // first time loading the page
  Smart::assign([
    'sources' => $relation->getSources(),
  ]);
}

Smart::addResources('sortable');
Smart::assign([
  'relation' => $relation,
  'fromEntity' => $fromEntity,
]);
Smart::display('relation/edit.tpl');

/*************************************************************************/

function validate($sources) {
  $errors = [];

  $countBadUrls = 0;
  foreach ($sources as $s) {
    if (!filter_var($s->url, FILTER_VALIDATE_URL)) {
      $countBadUrls++;
    }
  }
  if ($countBadUrls) {
    $errors['sources'][] = _('Some source URLS are invalid.');
  }

  return $errors;
}

function buildSources($relation, $ids, $urls) {
  $result = [];

  foreach ($ids as $i => $id) {
    $rs = $id
      ? RelationSource::get_by_id($id)
      : Model::factory('RelationSource')->create();
    $rs->url = $urls[$i];

    // ignore empty records
    if ($rs->url) {
      $result[] = $rs;
    }
  }

  return $result;
}
