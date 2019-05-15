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

  $relations = buildRelations(
    $entity,
    Request::getArray('relIds'),
    Request::getArray('relTypes'),
    Request::getArray('relEntityIds'),
    Request::getArray('relStartDates'),
    Request::getArray('relEndDates'));

  $deleteImage = Request::has('deleteImage');
  $imageData = Request::getImage('image');

  $errors = validate($entity, $relations, $imageData['status']);
  if (empty($errors)) {
    Img::saveWithImage($entity, $imageData, $deleteImage);

    Relation::updateDependants($relations, 'fromEntityId', $entity->id, 'rank');
    FlashMessage::add(_('Changes saved.'), 'success');
    Util::redirectToSelf();
  } else {
    Smart::assign('errors', $errors);
    Smart::assign('relations', $relations);
  }
} else {
  // first time loading the page
  Smart::assign('relations', $entity->getRelations());
}

Smart::addResources('select2Dev', 'sortable');
Smart::assign('entity', $entity);
Smart::display('entity/edit.tpl');

/*************************************************************************/

function validate($entity, $relations, $imageStatus) {
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
  $countBadMemberships = 0;
  foreach ($relations as $r) {
    $otherEntity = Entity::get_by_id($r->toEntityId);
    if (!$r->toEntityId) {
      $countNoEntityIds++;
    }
    if ($r->toEntityId == $entity->id) {
      $countSelf++;
    }
    if ($r->startDate && $r->endDate && ($r->startDate > $r->endDate)) {
      $countBadDates++;
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
    $errors['relations'][] = _('The start date cannot be past the end date.');
  }
  if ($countBadMemberships) {
    $errors['relations'][] = _(
      'Persons can be members of parties and parties can be members of unions. ' .
      'No other types of memberships are allowed.');
  }

  // image field
  $imgError = Img::validateImageStatus($imageStatus);
  if ($imgError) {
    $errors['image'][] = $imgError;
  }

  return $errors;
}

function buildRelations($entity, $ids, $types, $toEntityIds, $startDates, $endDates) {
  $result = [];

  foreach ($ids as $i => $id) {
    $r = $id
      ? Relation::get_by_id($id)
      : Model::factory('Relation')->create();
    $r->type = $types[$i];
    $r->fromEntityId = $entity->id;
    $r->toEntityId = $toEntityIds[$i];
    $r->startDate = $startDates[$i] ?: null;
    $r->endDate = $endDates[$i] ?: null;

    // ignore empty records
    if ($r->toEntityId || $r->startDate || $r->endDate) {
      $result[] = $r;
    }
  }

  return $result;
}
