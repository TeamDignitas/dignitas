{$g=Config::GOOGLE_TAG_MANAGER_ID}
{if $g}
  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id={$g}">
  </script>
  <script>
    {literal}
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    {/literal}
    gtag('config', '{$g}');
  </script>
{/if}
