const I18N_MESSAGES = {

  // alert and confirmation modals
  'alert-ok-text': 'OK',
  'confirm-ok-text': 'confirmă',
  'confirm-cancel-text': 'renunță',

  // archived version tooltip
  'archived-version-tooltip': 'Este disponibilă o <a href="%1">versiune arhivată</a> pentru această legătură.',

  // EasyMDE button tooltips
  'easymde-bold': 'aldin',
  'easymde-italic': 'cursiv',
  'easymde-heading': 'antet',
  'easymde-quote': 'citat',
  'easymde-unordered-list': 'listă generică',
  'easymde-ordered-list': 'listă numerotată',
  'easymde-link': 'creează o legătură',
  'easymde-image': 'inserează o imagine',
  'easymde-preview': 'previzualizare',
  'easymde-side-by-side': 'două coloane',
  'easymde-fullscreen': 'ecran complet',
  'easymde-resources': 'arată sugestiile de analiză',

  // remaining chars
  'remaining-chars': [
    'un caracter rămas',
    '%1 caractere rămase',
    '%1 de caractere rămase',
  ],
  'exceeding-chars': [
    'șterge un caracter',
    'șterge %1 caractere',
    'șterge %1 de caractere',
  ],

};

function _plural(n) {
  return (n == 1)
    ? 0 // un copil
    : ((n == 0 || (n % 100 > 0 && n % 100 < 20))
       ? 1   // 0 copii / 19 copii
       : 2); // 34 de copii
}
