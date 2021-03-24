/**
 * Returns a translated string for the given key.
 */
function _(key) {
  if (typeof I18N_MESSAGES == 'undefined') {
    return 'translation dictionary not loaded';
  }

  return I18N_MESSAGES[key] ?? ('undefined translation key: [' + key + ']');
}
