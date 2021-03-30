<?php

$id = Request::get('id');
$saveButton = Request::has('saveButton');
$deleteButton = Request::has('deleteButton');
$reopenButton = Request::has('reopenButton');
$referrer = Request::get('referrer');

if ($id) {
  $entity = Entity::get_by_id($id);
} else {
  $entity = Model::factory('Entity')->create();
  $entity->entityTypeId = EntityType::getDefaultId();
  $entity->userId = User::getActiveId();
}

if ($deleteButton) {
  if (!$entity->isDeletable()) {
    Snackbar::add(_('info-cannot-delete-entity'));
  } else {
    $entity->markDeleted(Ct::REASON_BY_USER);
    $entity->subscribe();
    $entity->notify();
    Action::create(Action::TYPE_DELETE, $entity);
    Snackbar::add(_('info-confirm-entity-deleted.'));
  }
  Util::redirectToHome();
}

if ($reopenButton) {
  if (!$entity->isReopenable()) {
    Snackbar::add(_('info-cannot-reopen-entity'));
  } else {
    // TODO this should be factored out in reopenEffects(), similar to markDeletedEffects().
    $entity->duplicateId = 0;

    $entity->reopen();
    $entity->subscribe();
    $entity->notify();
    Action::create(Action::TYPE_REOPEN, $entity);
    Snackbar::add(_('info-confirm-entity-reopened.'));
  }
  Util::redirect($entity->getViewUrl());
}

$entity->enforceEditPrivileges();

if ($saveButton) {
  $entity->name = Request::get('name');
  $entity->entityTypeId = Request::get('entityTypeId');
  $entity->profile = trim(Request::get('profile'));
  $color = $entity->hasColor() ? Request::get('color') : Entity::DEFAULT_COLOR;
  $entity->setColor($color);
  $entity->regionId = Request::get('regionId');
  if (User::isModerator()) {
    $entity->longPossessive = Request::get('longPossessive');
    $entity->shortPossessive = Request::get('shortPossessive');
  }

  $relations = buildRelations(
    Request::getArray('relIds'),
    Request::getArray('relTypes'),
    Request::getArray('relEntityIds'),
    Request::getArray('relStartDates'),
    Request::getArray('relEndDates'));
  $aliases = buildAliases(
    Request::getArray('aliasIds'),
    Request::getArray('aliasNames'));
  $links = Link::build(
    Request::getArray('linkIds'),
    Request::getArray('linkUrls'));

  $tagIds = Request::getArray('tagIds');

  $deleteImage = Request::has('deleteImage');
  $fileData = Request::getFile('image', 'Entity');

  $errors = validate($entity, $relations, $links, $fileData);
  if (empty($errors)) {
    $originalId = $entity->id;

    $entity = $entity->maybeClone();
    $entity->saveWithFile($fileData, $deleteImage);
    $entity->subscribe();
    $entity->notify();
    Action::createUpdateAction($entity, $originalId);

    Relation::updateDependants($relations, $entity, 'fromEntityId', 'rank');
    Alias::updateDependants($aliases, $entity, 'entityId', 'rank');
    Link::update($entity, $links);
    ObjectTag::update($entity, $tagIds);

    if (!$originalId) {
      Review::checkNewUser($entity);
      Snackbar::add(_('info-entity-added'));
      Util::redirect(Router::link('entity/view') . '/' . $entity->id);
    } else {
      if ($entity->status == Ct::STATUS_PENDING_EDIT) {
        Snackbar::add(_('info-changes-queued'));
      } else {
        Review::checkRecentlyClosedDeleted($entity);
        Snackbar::add(_('info-entity-updated'));
      }
      Util::redirect($referrer ?: $entity->getViewUrl());
    }
  } else {
    Snackbar::add(_('info-validation-error'));
    Smart::assign([
      'errors' => $errors,
      'referrer' => $referrer,
      'relations' => $relations,
      'aliases' => $aliases,
      'links' => $links,
      'tagIds' => $tagIds,
    ]);
  }
} else {
  // first time loading the page
  Smart::assign([
    'referrer' => Util::getReferrer(),
    'relations' => $entity->getRelations(),
    'aliases' => $entity->getAliases(),
    'links' => $entity->getLinks(),
    'tagIds' => ObjectTag::getTagIds($entity),
  ]);
}

Smart::addResources('datepicker', 'easymde', 'linkEditor');
Smart::assign([
  'entity' => $entity,
  'entityTypes' => EntityType::loadAll(),
  'regions' => Region::loadAll(),
]);
Smart::display('entity/edit.tpl');

/*************************************************************************/

function validate($entity, $relations, $links, $fileData) {
  $errors = [];

  // misc fields
  if (!$entity->name) {
    $errors['name'][] = _('info-must-enter-name');
  }

  if (!$entity->getEntityType()) {
    $errors['type'][] = _('info-incorrect-entity-type');
  }

  if (mb_strlen($entity->profile) > Entity::PROFILE_MAX_LENGTH) {
    $errors['profile'][] =
      sprintf(_('info-entity-profile-length-limit-%d'), Entity::PROFILE_MAX_LENGTH);
  }

  // outgoing relations (passed in the form)
  $relErrors = [];
  foreach ($relations as $r) {
    $relErrors = array_merge($relErrors, $r->validate($entity));
  }
  if (!empty($relErrors)) {
    $errors['relations'] = array_unique($relErrors);
  }

  // incoming relations (loaded from DB)
  if ($entity->id) {
    $incoming = Relation::get_all_by_toEntityId($entity->id);
    $incomingErrors = false;
    foreach ($incoming as $i) {
      if ($i->getRelationType()->toEntityTypeId != $entity->entityTypeId) {
        $incomingErrors = true;
      }
    }
    if ($incomingErrors) {
      $errors['entityTypeId'] = _('info-entity-type-change-invalidates-incoming-relations');
    }
  }

  // links
  $countBadUrls = 0;
  foreach ($links as $l) {
    if (!$l->validUrl()) {
      $countBadUrls++;
    }
  }
  if ($countBadUrls) {
    $errors['links'][] = _('info-invalid-entity-links');
  }

  // image field
  $fileError = UploadTrait::validateFileData($fileData);
  if ($fileError) {
    $errors['image'][] = $fileError;
  }

  return $errors;
}

function buildRelations($ids, $types, $toEntityIds, $startDates, $endDates) {
  $result = [];

  foreach ($ids as $i => $id) {
    $r = $id
      ? Relation::get_by_id($id)
      : Model::factory('Relation')->create();
    $r->relationTypeId = $types[$i];
    $r->toEntityId = $toEntityIds[$i];
    $r->startDate = $startDates[$i] ?: '0000-00-00';
    $r->endDate = $endDates[$i] ?: '0000-00-00';

    // ignore empty records
    if ($r->toEntityId ||
        ($r->startDate != '0000-00-00') ||
        ($r->endDate != '0000-00-00')) {
      $result[] = $r;
    }
  }

  return $result;
}

function buildAliases($ids, $names) {
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
