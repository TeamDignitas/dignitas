{* As directed by https://silktide.com/consent-manager/docs/google-consent-mode/ *}
{if Config::GOOGLE_TAG_MANAGER_ID}
  <script>
    // Initialize the dataLayer
    window.dataLayer = window.dataLayer || [];

    // Create the gtag function that pushes to the dataLayer
    function gtag() {
      dataLayer.push(arguments);
    }

    // Set consent defaults
    gtag('consent', 'default', {
      analytics_storage: localStorage.getItem('silktideCookieChoice_analytics') === 'true' ? 'granted' : 'denied',
      ad_storage: localStorage.getItem('silktideCookieChoice_advertising') === 'true' ? 'granted' : 'denied',
      ad_user_data: localStorage.getItem('silktideCookieChoice_advertising') === 'true' ? 'granted' : 'denied',
      ad_personalization: localStorage.getItem('silktideCookieChoice_advertising') === 'true' ? 'granted' : 'denied',
      functionality_storage: localStorage.getItem('silktideCookieChoice_necessary') === 'true' ? 'granted' : 'denied',
      security_storage: localStorage.getItem('silktideCookieChoice_necessary') === 'true' ? 'granted' : 'denied',
      region: [
        'AT', 'BE', 'BG', 'HR', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR',
        'DE', 'EL', 'HU', 'IE', 'IT', 'LV', 'LT', 'LU', 'MT', 'NL',
        'PL', 'PT', 'RO', 'SK', 'SI', 'ES', 'SE',
      ],
    });
  </script>
{/if}
