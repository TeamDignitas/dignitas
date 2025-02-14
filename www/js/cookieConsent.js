// Obtained from https://silktide.com/consent-manager/install/ and adapted:
//
// * translations;
// * wait until our (local) JS defines silktideCookieBannerManager.
//
// Do NOT include this code for Google Analytics. Instead, upload it in a
// Custom HTML tag in the Google Tag Manager. See:
//
// https://silktide.com/consent-manager/docs/google-consent-mode/
var silktidePromise = new Promise(function(resolve, reject) {
  var loop = function() {
    (typeof silktideCookieBannerManager !== 'undefined')
      ? resolve()
      : setTimeout(loop);
  }
  loop();
});

silktidePromise.then(function() {
  console.log("Only now updating config");
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
        id: "analytics",
        name: _('consent-analytics-name'),
        description: _('consent-analytics-description'),
        defaultValue: true,
        onAccept: function() {
          gtag('consent', 'update', {
            analytics_storage: 'granted',
          });
          dataLayer.push({
            'event': 'consent_accepted_analytics',
          });
        },
        onReject: function() {
          gtag('consent', 'update', {
            analytics_storage: 'denied',
          });
        }
      },
      {
        id: "advertising",
        name: _('consent-advertising-name'),
        description: _('consent-advertising-description'),
        onAccept: function() {
          gtag('consent', 'update', {
            ad_storage: 'granted',
            ad_user_data: 'granted',
            ad_personalization: 'granted',
          });
          dataLayer.push({
            'event': 'consent_accepted_advertising',
          });
        },
        onReject: function() {
          gtag('consent', 'update', {
            ad_storage: 'denied',
            ad_user_data: 'denied',
            ad_personalization: 'denied',
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
