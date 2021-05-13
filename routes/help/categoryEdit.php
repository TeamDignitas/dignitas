<?php

User::enforceModerator();

$id = Request::get('id');
$saveButton = Request::has('saveButton');
$deleteButton = Request::has('deleteButton');

if ($id) {
  $cat = HelpCategory::get_by_id($id);
} else {
  $cat = Model::factory('HelpCategory')->create();
  $cat->rank = 1 + Model::factory('HelpCategory')->count();
}

$translations = $cat->getAllTranslations();

// categories can be deleted if no links use them
$numPages = HelpPage::count_by_categoryId($cat->id);
$canDelete = $id && !$numPages;

if ($deleteButton) {
  if ($canDelete) {
    Snackbar::add(sprintf(_('info-help-category-deleted-%s'), $cat->name));
    $cat->delete();
    Util::redirectToRoute('help/index');
  } else {
    Snackbar::add(_('info-cannot-delete-help-category'));
    Util::redirectToSelf();
  }
}

if ($saveButton) {
  foreach (LocaleUtil::getAll() as $l => $ignored) {
    $translations[$l]->name = Request::get("name-{$l}");
    $translations[$l]->path = Request::get("path-{$l}");
  }

  $errors = validate($translations);
  if (empty($errors)) {
    $cat->save();

    foreach ($translations as $t) {
      if (!$t->isEmpty()) {
        $t->categoryId = $cat->id; // in case it was just added
        $t->save();
      } else if ($t->id) {
        $t->delete();
      }
    }

    $ids = Request::getArray('pageIds');
    $rank = 0;
    foreach ($ids as $id) {
      $p = HelpPage::get_by_id($id);
      $p->rank = ++$rank;
      $p->save();
    }

    Snackbar::add(_('info-help-category-saved'));
    Util::redirect($cat->getViewUrl());
  } else {
    Snackbar::add(_('info-validation-error'));
    Smart::assign('errors', $errors);
  }
}

Smart::assign([
  'cat' => $cat,
  'canDelete' => $canDelete,
  'translations' => $translations,
]);
Smart::addResources('sortable');
Smart::display('help/categoryEdit.tpl');

/*************************************************************************/

/**
 * @param HelpCategoryT[] $translations
 */
function validate($translations) {
  $errors = [];
  $paths = [];

  foreach ($translations as $locale => $hct) {
    $def = ($locale == Config::DEFAULT_LOCALE);

    // The default locale must be fully specified. Other locales may be fully
    // specified or entirely empty.
    if (!$hct->name && (!$hct->isEmpty() || $def)) {
      $errors['name'][$locale][] = _('info-must-enter-name');
    }

    if (!$hct->path && (!$hct->isEmpty() || $def)) {
      $errors['path'][$locale][] = _('info-must-enter-help-category-path');
    }

    // other checks
    if (!preg_match('/^[-a-z0-9]*$/', $hct->path)) {
      $errors['path'][$locale][] = _('info-help-path-syntax');
    }

    $existing = HelpCategoryT::get_by_path($hct->path);
    if ($existing && $existing->id != $hct->id) {
      $errors['path'][$locale][] = _('info-help-category-path-taken');
    }

    // ensure that all paths on this page are distinct from one another
    if ($hct->path) {
      if (isset($paths[$hct->path])) {
        $errors['path'][$locale][] = _('info-duplicate-path-in-form');
      }
      $paths[$hct->path] = true;
    }
  }

  return $errors;
}
