{$g=Config::GOOGLE_TAG_MANAGER_ID}
{if $g}
  <!-- Google Tag Manager -->
  <script>
    {literal}
    (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })
    {/literal}
    (window,document,'script','dataLayer','{$g}');</script>
  <!-- End Google Tag Manager -->
{/if}
