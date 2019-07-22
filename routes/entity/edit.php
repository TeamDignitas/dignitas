<?php

$id = Request::get('id');
$saveButton = Request::has('saveButton');
$deleteButton = Request::has('deleteButton');

if ($id) {
  $entity = Entity::get_by_id($id);
} else {
  $entity = Model::factory('Entity')->create();
  $entity->userId = User::getActiveId();
}

if ($deleteButton) {
  User::enforce(User::PRIV_DELETE_ENTITY);
  $entity->delete();
  FlashMessage::add(_('Entity deleted.'), 'success');
  Util::redirectToHome();
}

User::enforce($entity->id ? User::PRIV_EDIT_ENTITY : User::PRIV_ADD_ENTITY);

if ($saveButton) {
  $entity->name = Request::get('name');
  $entity->type = Request::get('type');
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

  $deleteImage = Request::has('deleteImage');
  $imageData = Request::getFile('image', 'Entity');

  $errors = validate($entity, $relations, $imageData);
  if (empty($errors)) {
    Img::saveWithImage($entity, $imageData, $deleteImage);

    Relation::updateDependants($relations, 'fromEntityId', $entity->id, 'rank');
    Alias::updateDependants($aliases, 'entityId', $entity->id, 'rank');
    FlashMessage::add(_('Changes saved.'), 'success');
    Util::redirect(Router::link('entity/edit') . '/' . $entity->id);
  } else {
    Smart::assign('errors', $errors);
    Smart::assign('relations', $relations);
    Smart::assign('aliases', $aliases);
  }
} else {
  // first time loading the page
  Smart::assign('relations', $entity->getRelations());
  Smart::assign('aliases', $entity->getAliases());
}

Smart::addResources('colorpicker', 'sortable');
Smart::assign('entity', $entity);
Smart::display('entity/edit.tpl');

/*************************************************************************/

function validate($entity, $relations, $imageData) {
  $errors = [];

  // misc fields
  if (!$entity->name) {
    $errors['name'][] = _('Please enter a name.');
  }

  if (!$entity->type) {
    $errors['type'][] = _('Please choose a type.');
  }

  // relations
  $countNoEntityIds = 0;
  $countSelf = 0;
  $countBadDates = 0;
  $countBadDateOrder = 0;
  $countBadMemberships = 0;
  foreach ($relations as $r) {
    $otherEntity = Entity::get_by_id($r->toEntityId);
    if (!$r->toEntityId) {
      $countNoEntityIds++;
    }
    if ($r->toEntityId == $entity->id) {
      $countSelf++;
    }
    if (!$r->startDate || !$r->endDate) {
      $countBadDates++;
    }
    if (($r->startDate != '0000-00-00') &&
        ($r->endDate != '0000-00-00') &&
        ($r->startDate > $r->endDate)) {
      $countBadDateOrder++;
    }
    if ($r->type == Relation::TYPE_MEMBER &&
        $otherEntity &&
        !Relation::validMembership($entity->type, $otherEntity->type)) {
      $countBadMemberships++;
    }
  }
  if ($countNoEntityIds) {
    $errors['relations'][] = _('Please choose a target entity.');
  }
  if ($countSelf) {
    $errors['relations'][] = _('An entity cannot be related to itself.');
  }
  if ($countBadDates) {
    $errors['relations'][] = _('Some of the dates are invalid.');
  } else if ($countBadDateOrder) {
    $errors['relations'][] = _('The start date cannot be past the end date.');
  }
  if ($countBadMemberships) {
    $errors['relations'][] = _(
      'Persons can be members of parties and parties can be members of unions. ' .
      'No other types of memberships are allowed.');
  }

  // image field
  $imgError = Img::validateImageData($imageData);
  if ($imgError) {
    $errors['image'][] = $imgError;
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
