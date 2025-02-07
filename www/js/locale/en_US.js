/**
 * An array of key: translation pairs. Should define all the keys for which
 * _() is called. Values can be strings or, for singular/plural distinctions,
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

  // cookie consent
  'consent-necessary-name': 'Necessary',
  'consent-necessary-description': '<p>These cookies are necessary for the website to function properly and cannot be switched off. They help with things like logging in and setting your privacy preferences.</p>',
  'consent-analytical-name': 'Analytical',
  'consent-analytical-description': '<p>These cookies help us improve the site by tracking which pages are most popular and how visitors move around the site.</p>',

  'consent-banner-description': '<p>We use cookies on our site to enhance your user experience, provide personalized content, and analyze our traffic.</p>',
  'consent-banner-accept': 'Accept all',
  'consent-banner-accept-label': 'Accept all cookies',
  'consent-banner-reject': 'Reject non-essential',
  'consent-banner-reject-label': 'Reject non-essential cookies',
  'consent-banner-prefs': 'Preferences',
  'consent-banner-prefs-label': 'Toggle preferences',

  'consent-prefs-title': 'Customize your cookie preferences',
  'consent-prefs-description': '<p>We respect your right to privacy. You can choose not to allow some types of cookies. Your cookie preferences will apply across our website.</p>',
  'consent-prefs-credit': 'Get this banner for free',
  'consent-prefs-credit-label': 'Get this banner for free',
};

/**
 * Returns the type of singular/plural form to be used for this value. The
 * corresponding array element will be returned from I18N_MESSAGES.
 * See ro_RO.js for a more complex rule.
 */
function _plural(n) {
  return (n == 1) ? 0 : 1;
}
