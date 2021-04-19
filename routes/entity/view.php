<?php

$id = Request::get('id');

$entity = Entity::get_by_id($id);
if (!$entity) {
  Snackbar::add(_('info-no-such-entity'));
  Util::redirectToHome();
}

if ($entity->hasPendingEdit() && User::may(User::PRIV_REVIEW)) {
  Smart::assign([
    'pendingEditReview' => Review::getForObject($entity, Ct::REASON_PENDING_EDIT),
  ]);
}

Smart::assign([
  'entity' => $entity,
  'statements' => $entity->getStatements(),
  'mentions' => $entity->getInvolvementStatements(),
]);
Smart::addResources('flag', 'subscribe');
Smart::display('entity/view.tpl');
