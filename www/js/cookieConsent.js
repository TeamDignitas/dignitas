// Obtained from https://silktide.com/consent-manager/install/,
// then personalized for translations.
//
// Do NOT include this code for Google Analytics. Instead, upload it in a
// Custom HTML tag in the Google Tag Manager. See:
//
// https://silktide.com/consent-manager/docs/google-consent-mode/
$(function() {
  silktideCookieBannerManager.updateCookieBannerConfig({
    background: {
      showBackground: false
    },
    cookieIcon: {
      position: "bottomRight"
    },
    cookieTypes: [
      {
        id: "necessary",
        name: _('consent-necessary-name'),
        description: _('consent-necessary-description'),
        required: true,
        onAccept: function() {
          // console.log('Add logic for the required Necessary here');
        }
      },
      {
        id: "analytical",
        name: _('consent-analytical-name'),
        description: _('consent-analytical-description'),
        required: false,
        onAccept: function() {
          gtag('consent', 'update', {
            analytics_storage: 'granted',
          });
          dataLayer.push({
            'event': 'consent_accepted_analytical',
          });
        },
        onReject: function() {
          gtag('consent', 'update', {
            analytics_storage: 'denied',
          });
        }
      }
    ],
    text: {
      banner: {
        description: _('consent-banner-description'),
        acceptAllButtonText: _('consent-banner-accept'),
        acceptAllButtonAccessibleLabel: _('consent-banner-accept-label'),
        rejectNonEssentialButtonText: _('consent-banner-reject'),
        rejectNonEssentialButtonAccessibleLabel: _('consent-banner-reject-label'),
        preferencesButtonText: _('consent-banner-prefs'),
        preferencesButtonAccessibleLabel: _('consent-banner-prefs-label'),
      },
      preferences: {
        title: _('consent-prefs-title'),
        description: _('consent-prefs-description'),
        creditLinkText: _('consent-prefs-credit'),
        creditLinkAccessibleLabel: _('consent-prefs-credit-label'),
      }
    }
  });
});
