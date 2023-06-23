<?php

const STATEMENT_LIMIT = 10;
const ANSWER_LIMIT = 10;
const ENTITY_LIMIT = 10;

$id = Request::get('id');
$saveButton = Request::has('saveButton');
$cloneButton = Request::has('cloneButton');
$deleteButton = Request::has('deleteButton');

if ($id) {
  $tag = Tag::get_by_id($id);
  $tag->loadSubtree();
} else {
  $tag = Tag::create();
}

// tags can be deleted if (1) they have no children and (2) no objects use them
$used = ObjectTag::get_by_tagId($tag->id);
$canDelete = empty($tag->children) && !$used;

if ($deleteButton) {
  if ($tag->isDeletable() && $canDelete) {
    Snackbar::add(sprintf(_('tag-deleted-%s'), $tag->value));
    Action::create(Action::TYPE_DELETE, $tag);
    $tag->delete();
    Util::redirectToRoute('tag/list');
  } else {
    Snackbar::add(_('info-cannot-delete-tag'));
    Util::redirect("/{$tag->id}");
  }
}

User::enforce($tag->id ? User::PRIV_EDIT_TAG : User::PRIV_ADD_TAG);

if (Ban::exists(Ban::TYPE_TAG)) {
  Snackbar::add(_('info-banned-tag'));
  Util::redirectToHome();
}

if ($cloneButton) {
  if ($tag->id) {
    Snackbar::add(_('info-tag-cloned'));
    $clone = $tag->parisClone();
    $clone->value .= sprintf(' (%s)', _('label-clone'));
    $clone->save();
    Util::redirect($clone->getEditUrl());
  } else {
    // unreachable via normal UI actions
    Snackbar::add(_('info-save-tag-before-clone'));
    Util::redirect($tag->getEditUrl());
  }
}

if ($saveButton) {
  $tag->value = Request::get('value');
  $tag->parentId = Request::get('parentId', 0);
  $tag->color = Request::get('color');
  $tag->icon = Request::get('icon');
  $tag->iconOnly = Request::has('iconOnly');
  $tag->tooltip = Request::get('tooltip');
  $tag->visAnon = Request::has('visAnon');

  $errors = validate($tag);
  if (empty($errors)) {
    $new = !$tag->id;
    $tag->save();
    Action::create(
      $new ? Action::TYPE_CREATE : Action::TYPE_UPDATE,
      $tag);

    Snackbar::add(_('info-tag-saved'));
    Util::redirect(Router::link('tag/list'));
  } else {
    Smart::assign('errors', $errors);
  }
}

$homonyms = Model::factory('Tag')
  ->where('value', $tag->value)
  ->where_not_equal('id', $tag->id)
  ->find_many();

Smart::assign([
  't' => $tag,
  'canDelete' => $canDelete,
  'homonyms' => $homonyms,
]);
Smart::addResources('bootstrap-select');
Smart::display('tag/edit.tpl');

/*************************************************************************/

function validate($tag) {
  $errors = [];

  if (!$tag->value) {
    $errors['value'][] = _('info-must-enter-name');
  }

  // make sure the chosen parent is not also a descendant - no cycles allowed
  $p = $tag;
  do {
    $p = Tag::get_by_id($p->parentId);
  } while ($p && ($p->id != ($tag->id)));
  if ($p) {
    $errors['parentId'][] = _('info-tag-loop');
  }

  return $errors;
}
