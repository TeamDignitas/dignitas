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

$sq = $entity->getStatementQuery();
$iq = $entity->getInvolvementQuery();
$miq = $entity->getMemberInvolvementQuery();
$trustLevel = TrustLevel::getForEntity($entity);

Smart::assign([
  'entity' => $entity,
  'numStatements' => $sq->count(),
  'statements' => Statement::getPage($sq, 1),
  'statementPages' => Statement::getNumPages($sq),
  'members' => $entity->getMembers(),
  'numMentions' => $iq->count(),
  'numMemberMentions' => $miq->count(),
  'mentions' => Statement::getPage($iq, 1),
  'mentionPages' => Statement::getNumPages($iq),
  'trustLevel' => $trustLevel,
]);
Smart::addResources('flag', 'pagination', 'subscribe');
Smart::display('entity/view.tpl');
