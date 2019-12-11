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

if (!$entity->isEditable() && !User::canSuggestEdits()) {
  User::enforce($entity->id ? User::PRIV_EDIT_ENTITY : User::PRIV_ADD_ENTITY);
}

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
  $links = EntityLink::build(
    Request::getArray('urlIds'),
    Request::getArray('urls'));

  $deleteImage = Request::has('deleteImage');
  $fileData = Request::getFile('image', 'Entity');

  $errors = validate($entity, $relations, $links, $fileData);
  if (empty($errors)) {
    $new = !$entity->id;

    // this can cause two saves at the moment
    $refs = [];
    $entity = $entity->saveOrClone($refs);
    $entity->saveWithFile($fileData, $deleteImage);

    Relation::updateDependants($relations, 'fromEntityId', $entity->id, 'rank', $refs);
    Alias::updateDependants($aliases, 'entityId', $entity->id, 'rank', $refs);
    EntityLink::updateDependants($links, 'entityId', $entity->id, 'rank', $refs);

    if ($new) {
      Review::checkNewUser($entity);
      FlashMessage::add(_('Author added.'), 'success');
      Util::redirect(Router::link('entity/view') . '/' . $entity->id);
    } else {
      if ($entity->status == Ct::STATUS_PENDING_EDIT) {
        FlashMessage::add(_('Your changes were placed in the review queue.'), 'success');
      } else {
        FlashMessage::add(_('Author updated.'), 'success');
      }
      Util::redirect($referrer
                     ? $referrer
                     : (Router::link('entity/view') . '/' . $entity->id));
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

Smart::addResources('colorpicker', 'simplemde', 'urlEditor');
Smart::assign('entity', $entity);
Smart::display('entity/edit.tpl');

/*************************************************************************/

function validate($entity, $relations, $links, $fileData) {
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

  // links
  $countBadUrls = 0;
  foreach ($links as $l) {
    if (!$l->validUrl()) {
      $countBadUrls++;
    }
  }
  if ($countBadUrls) {
    $errors['links'][] = _('Some link URLS are invalid.');
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
