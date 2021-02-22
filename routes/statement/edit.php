<?php

$id = Request::get('id');
// prepopulated so we can quickly add statements from the entity page
$entityId = Request::get('entityId');
$saveButton = Request::has('saveButton');
$deleteButton = Request::has('deleteButton');
$reopenButton = Request::has('reopenButton');
$referrer = Request::get('referrer');

if ($id) {
  $statement = Statement::get_by_id($id);
} else {
  $statement = Model::factory('Statement')->create();
  $statement->dateMade = Time::today();
  $statement->type = Statement::TYPE_CLAIM;
  $statement->userId = User::getActiveId();
  $statement->entityId = $entityId;
}

if ($deleteButton) {
  if (!$statement->isDeletable()) {
    Snackbar::add(_('info-cannot-delete-statement'));
    Util::redirectToSelf();
  }
  $statement->markDeleted(Ct::REASON_BY_USER);
  $statement->subscribe();
  $statement->notify();
  Action::create(Action::TYPE_DELETE, $statement);
  Snackbar::add(_('info-confirm-statement-deleted'), 'success');
  Util::redirectToHome();
}

if ($reopenButton) {
  if (!$statement->isReopenable()) {
    Snackbar::add(_('info-cannot-reopen-statement'));
  } else {
    // TODO this should be factored out in reopenEffects(), similar to markDeletedEffects().
    $statement->duplicateId = 0;

    $statement->reopen();
    $statement->subscribe();
    $statement->notify();
    Action::create(Action::TYPE_REOPEN, $statement);
    Snackbar::add(_('info-confirm-statement-reopened.'), 'success');
  }
  Util::redirect($statement->getViewUrl());
}

$statement->enforceEditPrivileges();

if ($saveButton) {
  $statement->entityId = $entityId;
  $statement->summary = Request::get('summary');
  $statement->context = Request::get('context');
  $statement->goal = Request::get('goal');
  $statement->dateMade = Request::get('dateMade');
  $origType = $statement->type;
  $statement->type = Request::get('type');
  if (User::isModerator()) {
    $origVerdict = $statement->verdict;
    $statement->verdict = Request::get('verdict');
  }

  $links = Link::build(
    Request::getArray('linkIds'),
    Request::getArray('linkUrls'));

  $tagIds = Request::getArray('tagIds');

  $errors = validate($statement, $links);
  if (empty($errors)) {
    $originalId = $statement->id;
    $statement = $statement->maybeClone();
    $statement->save();
    $statement->subscribe();
    $statement->notify();
    Action::createUpdateAction($statement, $originalId);

    if (!$originalId) {
      Review::checkNewUser($statement);
    }
    Review::checkRecentlyClosedDeleted($statement);
    Link::update($statement, $links);
    ObjectTag::update($statement, $tagIds);

    // grant verdict reputation
    if (User::isModerator()) {
      $hadVerdict = ($origVerdict != Statement::VERDICT_NONE);
      $hasVerdict = ($statement->verdict != Statement::VERDICT_NONE);
      if ($hadVerdict ^ $hasVerdict) {
        $u = User::get_by_id($statement->userId);
        $sign = $hasVerdict ? +1 : -1;
        $u->grantReputation($sign * Config::REP_VERDICT);
      }
    }

    if ($origType != $statement->type) {
      $answers = Answer::get_all_by_statementId($statement->id);
      foreach ($answers as $answer) {
        $answer->verdict = Statement::VERDICT_NONE;
        $answer->save();
      }
    }

    if (!$originalId) {
      Snackbar::add(_('info-statement-added'), 'success');
      Util::redirect(Router::link('statement/view') . '/' . $statement->id);
    } else {
      if ($statement->status == Ct::STATUS_PENDING_EDIT) {
        Snackbar::add(_('info-changes-queued'), 'success');
      } else {
        Snackbar::add(_('info-statement-updated'), 'success');
      }
      Util::redirect($referrer ?: $statement->getViewUrl());
    }
  } else {
    Snackbar::add(_('info-validation-error'));
    Smart::assign([
      'errors' => $errors,
      'referrer' => $referrer,
      'links' =>  $links,
      'tagIds' => $tagIds,
    ]);
  }
} else {
  // first time loading the page
  Smart::assign([
    'referrer' => Util::getReferrer(),
    'links' => $statement->getLinks(),
    'tagIds' => ObjectTag::getTagIds($statement),
  ]);
}

Smart::addResources('imageModal', 'datepicker', 'easymde', 'linkEditor');
Smart::assign('statement', $statement);
Smart::display('statement/edit.tpl');

/*************************************************************************/

function validate($statement, $links) {
  $errors = [];

  if (!$statement->entityId) {
    $errors['entityId'][] = _('info-must-enter-statement-entity');
  }

  if (!$statement->summary) {
    $errors['summary'][] = _('info-must-enter-statement-summary');
  }
  if (mb_strlen($statement->summary) > Statement::MAX_SUMMARY_LENGTH) {
    $errors['summary'][] = sprintf(
      _('info-field-length-limit-%d'), Statement::MAX_SUMMARY_LENGTH);
  }

  if (!$statement->context) {
    $errors['context'][] = _('info-must-enter-statement-context');
  }

  if (!$statement->goal) {
    $errors['goal'][] = _('info-must-enter-statement-goal');
  }
  if (mb_strlen($statement->goal) > Statement::MAX_GOAL_LENGTH) {
    $errors['goal'][] = sprintf(
      _('info-field-length-limit-%d'), Statement::MAX_GOAL_LENGTH);
  }

  if (!$statement->dateMade) {
    $errors['dateMade'][] = _('info-must-enter-statement-date');
  } else if ($statement->dateMade > Time::today()) {
    $errors['dateMade'][] = _('info-date-not-future');
  }

  $countBadUrls = 0;
  foreach ($links as $l) {
    if (!$l->validUrl()) {
      $countBadUrls++;
    }
  }
  if ($countBadUrls) {
    $errors['links'][] = _('info-invalid-statement-links');
  }

  // Check that the verdict matches the statement type. This should not happen
  // with normal usage because JS sets the verdict to none when the type changes.
  if (!in_array($statement->verdict, $statement->getVerdictChoices())) {
    $errors['verdict'][] = _('info-mismatch-statement-type-verdict');
  }

  return $errors;
}
