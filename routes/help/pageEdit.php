<?php

User::enforceModerator();

$id = Request::get('id');
$saveButton = Request::has('saveButton');
$deleteButton = Request::has('deleteButton');

if ($id) {
  $page = HelpPage::get_by_id($id);
} else {
  $page = Model::factory('HelpPage')->create();
}

if ($deleteButton) {
  Log::notice('deleted help page %d [%s]', $page->id, $page->title);
  Snackbar::add(_('info-help-page-deleted'));
  $page->delete();
  Util::redirectToRoute('help/index');
}

$translations = $page->getAllTranslations();

if ($saveButton) {
  $page->categoryId = Request::get('categoryId');
  $page->assignNewRank();

  foreach (LocaleUtil::getAll() as $l => $ignored) {
    $translations[$l]->title = Request::get("title-{$l}");
    $translations[$l]->path = Request::get("path-{$l}");
    $translations[$l]->contents = Request::get("contents-{$l}");
  }

  $errors = validate($page, $translations);
  if (empty($errors)) {
    $page->save();

    foreach ($translations as $t) {
      if (!$t->isEmpty()) {
        $t->pageId = $page->id; // in case it was just added
        $t->save();
      } else if ($t->id) {
        $t->delete();
      }
    }

    Snackbar::add(_('info-help-page-saved'));
    Util::redirect($page->getViewUrl());
  } else {
    Snackbar::add(_('info-validation-error'));
    Smart::assign('errors', $errors);
  }
}

Smart::assign([
  'page' => $page,
  'translations' => $translations,
]);
Smart::addResources('easymde');
Smart::display('help/pageEdit.tpl');

/*************************************************************************/

/**
 * @param HelpPageT[] $translations
 */
function validate(HelpPage $page, array $translations) {
  $errors = [];

  if (!$page->categoryId) {
    $errors['categoryId'][] = _('info-must-select-help-page-category');
  }

  foreach ($translations as $locale => $hpt) {
    $def = ($locale == Config::DEFAULT_LOCALE);

    // The default locale must be fully specified. Other locales may be fully
    // specified or entirely empty.
    if (!$hpt->title && (!$hpt->isEmpty() || $def)) {
      $errors['title'][$locale][] = _('info-must-enter-help-page-title');
    }

    if (!$hpt->path && (!$hpt->isEmpty() || $def)) {
      $errors['path'][$locale][] = _('info-must-enter-help-page-path');
    }

    if (!$hpt->contents && (!$hpt->isEmpty() || $def)) {
      $errors['contents'][$locale][] = _('info-must-enter-help-page-contents');
    }

    // other checks
    if (!preg_match('/^[-a-z0-9]*$/', $hpt->path)) {
      $errors['path'][$locale][] = _('info-help-path-syntax');
    }

    $existing = HelpPageT::get_by_path($hpt->path);
    if ($existing && $existing->id != $hpt->id) {
      $errors['path'][$locale][] = _('info-help-page-path-taken');
    }

  }

  return $errors;
}
