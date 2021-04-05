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
} else {
  $tag = Model::factory('Tag')->create();
}

// tags can be deleted if (1) they have no children and (2) no objects use them
$children = Model::factory('Tag')
  ->where('parentId', $tag->id)
  ->order_by_asc('value')
  ->find_many();
$used = ObjectTag::get_by_tagId($tag->id);
$canDelete = empty($children) && !$used;

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
  $tag->setColor(Request::get('color'));
  $tag->setBackground(Request::get('background'));
  $tag->icon = Request::get('icon');
  $tag->iconOnly = Request::has('iconOnly');
  $tag->tooltip = Request::get('tooltip');

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

$frequentColors = [
  'color' => Tag::getFrequentValues('color', Tag::DEFAULT_COLOR),
  'background' => Tag::getFrequentValues('background', Tag::DEFAULT_BACKGROUND),
];

$homonyms = Model::factory('Tag')
  ->where('value', $tag->value)
  ->where_not_equal('id', $tag->id)
  ->find_many();

Smart::assign([
  't' => $tag,
  'children' => $children,
  'canDelete' => $canDelete,
  'homonyms' => $homonyms,
  'frequentColors' => $frequentColors,
]);
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
