/**
 * An array of key: translation pairs. Should define all the keys for which
 * _() is called. Values can be strings or, for singular/plursl distinctions,
 * arrays of strings. For arrays, the length should match the number of values
 * returned by _plural().
 */
const I18N_MESSAGES = {

  // alert and confirmation modals
  'alert-ok-text': 'OK',
  'confirm-ok-text': 'OK',
  'confirm-cancel-text': 'cancel',

  // archived version tooltip
  'archived-version-tooltip': 'An <a href="%1">archived version</a> is available for this link.',

  // EasyMDE button tooltips
  'easymde-bold': 'bold',
  'easymde-italic': 'italic',
  'easymde-heading': 'heading',
  'easymde-quote': 'quote',
  'easymde-unordered-list': 'generic list',
  'easymde-ordered-list': 'numbered list',
  'easymde-link': 'create link',
  'easymde-image': 'insert image',
  'easymde-preview': 'toggle preview',
  'easymde-side-by-side': 'toggle side by side',
  'easymde-fullscreen': 'toggle fullscreen',
  'easymde-resources': 'show answer resources',

  // remaining chars
  'remaining-chars': [
    'one character left',
    '%1 characters left',
  ],
  'exceeding-chars': [
    'delete one character',
    'delete %1 characters',
  ],
};

/**
 * Returns the type of singular/plural form to be used for this value. The
 * corresponding array element will be returned from I18N_MESSAGES.
 * See ro_RO.js for a more complex rule.
 */
function _plural(n) {
  return (n == 1) ? 0 : 1;
}
