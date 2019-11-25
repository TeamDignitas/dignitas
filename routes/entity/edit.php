<?php

$id = Request::get('id');
$saveButton = Request::has('saveButton');
$deleteButton = Request::has('deleteButton');
$referrer = Request::get('referrer');

if ($id) {
  $entity = Entity::get_by_id($id);
} else {
  $entity = Model::factory('Entity')->create();
  $entity->userId = User::getActiveId();
}

if ($deleteButton) {
  if (!$entity->isDeletable()) {
    FlashMessage::add(_('You cannot delete this entity.'));
  } else {
    $entity->markDeleted(Ct::REASON_BY_USER);
    FlashMessage::add(_('Author deleted.'), 'success');
  }
  Util::redirectToHome();
}

User::enforce($entity->id ? User::PRIV_EDIT_ENTITY : User::PRIV_ADD_ENTITY);

if ($saveButton) {
  $entity->name = Request::get('name');
  $entity->type = Request::get('type');
  $entity->profile = Request::get('profile');
  $color = $entity->hasColor() ? Request::get('color') : Entity::DEFAULT_COLOR;
  $entity->setColor($color);

  $relations = buildRelations(
    $entity,
    Request::getArray('relIds'),
    Request::getArray('relTypes'),
    Request::getArray('relEntityIds'),
    Request::getArray('relStartDatesY'),
    Request::getArray('relStartDatesM'),
    Request::getArray('relStartDatesD'),
    Request::getArray('relEndDatesY'),
    Request::getArray('relEndDatesM'),
    Request::getArray('relEndDatesD'));
  $aliases = buildAliases(
    $entity,
    Request::getArray('aliasIds'),
    Request::getArray('aliasNames'));
  $links = buildLinks(
    $entity,
    Request::getArray('linkIds'),
    Request::getArray('linkUrls'));

  $deleteImage = Request::has('deleteImage');
  $fileData = Request::getFile('image', 'Entity');

  $errors = validate($entity, $relations, $fileData);
  if (empty($errors)) {
    $new = !$entity->id;
    $entity->saveWithFile($fileData, $deleteImage);

    Relation::updateDependants($relations, 'fromEntityId', $entity->id, 'rank');
    Alias::updateDependants($aliases, 'entityId', $entity->id, 'rank');
    EntityLink::updateDependants($links, 'entityId', $entity->id, 'rank');

    if ($new) {
      Review::checkNewUser($entity);
      FlashMessage::add(_('Author added.'), 'success');
      Util::redirect(Router::link('entity/view') . '/' . $entity->id);
    } else {
      FlashMessage::add(_('Author updated.'), 'success');
      Util::redirect($referrer);
    }
  } else {
    Smart::assign([
      'errors' => $errors,
      'referrer' => $referrer,
      'relations' => $relations,
      'aliases' => $aliases,
      'links' => $links,
    ]);
  }
} else {
  // first time loading the page
  Smart::assign([
    'referrer' => Util::getReferrer(),
    'relations' => $entity->getRelations(),
    'aliases' => $entity->getAliases(),
    'links' => $entity->getLinks(),
  ]);
}

Smart::addResources('colorpicker', 'simplemde', 'sortable');
Smart::assign('entity', $entity);
Smart::display('entity/edit.tpl');

/*************************************************************************/

function validate($entity, $relations, $fileData) {
  $errors = [];

  // misc fields
  if (!$entity->name) {
    $errors['name'][] = _('Please enter a name.');
  }

  if (!$entity->type) {
    $errors['type'][] = _('Please choose a type.');
  }

  // relations
  $relErrors = [];
  foreach ($relations as $r) {
    $relErrors = array_merge($relErrors, $r->validate($entity));
  }
  if (!empty($relErrors)) {
    $errors['relations'] = array_unique($relErrors);
  }

  // image field
  $fileError = UploadTrait::validateFileData($fileData);
  if ($fileError) {
    $errors['image'][] = $fileError;
  }

  return $errors;
}

function buildRelations($entity, $ids, $types, $toEntityIds,
                        $startYears, $startMonths, $startDays,
                        $endYears, $endMonths, $endDays) {
  $result = [];

  foreach ($ids as $i => $id) {
    $r = $id
      ? Relation::get_by_id($id)
      : Model::factory('Relation')->create();
    $r->type = $types[$i];
    $r->fromEntityId = $entity->id;
    $r->toEntityId = $toEntityIds[$i];
    $r->startDate = Time::partialDate($startYears[$i], $startMonths[$i], $startDays[$i]);
    $r->endDate = Time::partialDate($endYears[$i], $endMonths[$i], $endDays[$i]);

    // ignore empty records
    if ($r->toEntityId ||
        ($r->startDate != '0000-00-00') ||
        ($r->endDate != '0000-00-00')) {
      $result[] = $r;
    }
  }

  return $result;
}

function buildAliases($entity, $ids, $names) {
  $result = [];

  foreach ($ids as $i => $id) {
    $a = $id
      ? Alias::get_by_id($id)
      : Model::factory('Alias')->create();
    $a->name = $names[$i];

    // ignore empty records
    if ($a->name) {
      $result[] = $a;
    }
  }

  return $result;
}

function buildLinks($entity, $ids, $urls) {
  $result = [];

  foreach ($ids as $i => $id) {
    $link = $id
      ? EntityLink::get_by_id($id)
      : Model::factory('EntityLink')->create();
    $link->url = $urls[$i];

    // ignore empty records
    if ($link->url) {
      $result[] = $link;
    }
  }

  return $result;
}
