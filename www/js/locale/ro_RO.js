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

  // donations
  'donate-minimum-amount': 'Suma minimă pe care o poți dona este %1 %2.',

  // cookie consent
  'consent-necessary-name': 'Necesare',
  'consent-necessary-description': '<p>Aceste cookie-uri sînt necesare pentru ca site-ul să funcționeze și nu pot fi dezactivate. Cu ele implementăm autentificarea și îți stocăm preferințele de intimitate.</p>',
  'consent-analytics-name': 'Analitice',
  'consent-analytics-description': '<p>Aceste cookie-uri ne ajută să îmbunătățim site-ul urmărind ce pagini sînt cele mai populare și cum navighează utilizatorii prin site.</p>',
  'consent-advertising-name': 'Publicitate',
  'consent-advertising-description': '<p>Aceste cookie-uri oferă funcții suplimentare și personalizare ca să îți îmbunătățim experiența. Pot fi salvate de noi sau de parteneri ai căror servicii le folosim.</p>',

  'consent-banner-description': '<p>Folosim cookie-uri pe site ca să îți îmbunătățim experiența ca utilizator, să îți oferim conținut personalizat și să analizăm traficul.</p>',
  'consent-banner-accept': 'Acceptă toate',
  'consent-banner-accept-label': 'Acceptă toate cookie-urile',
  'consent-banner-reject': 'Respinge neesențiale',
  'consent-banner-reject-label': 'Respinge cookie-urile neesențiale',
  'consent-banner-prefs': 'Preferințe',
  'consent-banner-prefs-label': 'Arată preferințele',

  'consent-prefs-title': 'Personalizează preferințele pentru cookie-uri',
  'consent-prefs-description': '<p>Îți respectăm dreptul la intimitate. Poți alege să nu permiți anumite tipuri de cookie-uri. Aceste preferințe se vor aplica pe întregul site.</p>',
  'consent-prefs-credit': 'Obține gratuit acest banner',
  'consent-prefs-credit-label': 'Obține gratuit acest banner',
};

function _plural(n) {
  return (n == 1)
    ? 0 // un copil
    : ((n == 0 || (n % 100 > 0 && n % 100 < 20))
       ? 1   // 0 copii / 19 copii
       : 2); // 34 de copii
}
