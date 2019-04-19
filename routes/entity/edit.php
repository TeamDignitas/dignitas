<?php

Util::assertLoggedIn();

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
  $entity->delete();
  FlashMessage::add(_('Entity deleted.'), 'success');
  Util::redirectToHome();
}

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

  $errors = validate($entity, $relations);
  if (empty($errors)) {
    $entity->save();
    Relation::updateDependants($relations, 'fromEntityId', $entity->id, 'rank');
    FlashMessage::add(_('Changes saved.'), 'success');
    Util::redirect(Router::link('entity/edit') . '/' . $entity->id);
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

function validate($entity, $relations) {
  $errors = [];

  if (!$entity->name) {
    $errors['name'][] = _('Please enter a name.');
  }

  if (!$entity->type) {
    $errors['type'][] = _('Please choose a type.');
  }

  $countNoEntityIds = 0;
  $countSelf = 0;
  $countBadDates = 0;
  $countPersonMembers = 0;
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
        $otherEntity->type == Entity::TYPE_PERSON) {
      $countPersonMembers++;
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
  if ($countPersonMembers) {
    $errors['relations'][] = _('An entity cannot be a member of a person.');
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
