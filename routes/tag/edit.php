<?php

const STATEMENT_LIMIT = 10;
const ANSWER_LIMIT = 10;
const ENTITY_LIMIT = 10;

$id = Request::get('id');
$saveButton = Request::has('saveButton');
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
  User::enforce(User::PRIV_DELETE_TAG);
  if ($canDelete) {
    FlashMessage::add(sprintf(_('Tag «%s» deleted.'), $tag->value), 'success');
    $tag->delete();
    Util::redirectToRoute('tag/list');
  } else {
    FlashMessage::add(
      'Cannot delete this tag because (1) it has descendants or (2) it is being used.',
      'danger');
    Util::redirect("/{$tag->id}");
  }
}

User::enforce($tag->id ? User::PRIV_EDIT_TAG : User::PRIV_ADD_TAG);

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
    $tag->save();

    FlashMessage::add(_('Tag saved.'), 'success');
    Util::redirect(Router::link('tag/edit') . '/' . $tag->id);
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

// TODO: objects and counts

Smart::assign([
  't' => $tag,
  'children' => $children,
  'canDelete' => $canDelete,
  'homonyms' => $homonyms,
  'frequentColors' => $frequentColors,
]);
Smart::addResources('colorpicker');
Smart::display('tag/edit.tpl');

/*************************************************************************/

function validate($tag) {
  $errors = [];

  if (!$tag->value) {
    $errors['value'][] = _('Please enter a tag name.');
  }

  // make sure the chosen parent is not also a descendant - no cycles allowed
  $p = $tag;
  do {
    $p = Tag::get_by_id($p->parentId);
  } while ($p && ($p->id != ($tag->id)));
  if ($p) {
    $errors['parentId'][] = _('You cannot select a descendant as the parent.');
  }

  return $errors;
}
