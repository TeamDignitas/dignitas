{$pageType=$pageType|default:'other'}
<!DOCTYPE html>
<html>

  <head>
    {include "bits/consentTypes.tpl"}
    {include "bits/googleTagManagerHead.tpl"}
    <title>
      {block "title"}{/block}
      {if $pageType != 'home'}| Dignitas{/if}
    </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    {block "metaDescription"}{/block}
    {include "bits/colorScheme.tpl"}

    <link rel="icon" type="image/svg+xml" href="{Config::URL_PREFIX}img/favicon.svg">

    {* Preload some fonts. Note that this will be wasted for browsers that *}
    {* don't support WOFF2. *}
    {include "bits/preloadFont.tpl" font="spartan-v1-latin-ext_latin-regular"}
    {include "bits/preloadFont.tpl" font="spartan-v1-latin-ext_latin-500"}
    {include "bits/preloadFont.tpl" font="material-icons"}

    <link rel="preload" href="{$cssFile.path}?v={$cssFile.date}" as="style">
    <link href="{$cssFile.path}?v={$cssFile.date}" rel="stylesheet" type="text/css">

    {include "bits/relAlternate.tpl"}
    {include "bits/phpConstants.tpl"}
    <script src="{$jsFile.path}?v={$jsFile.date}" defer></script>
    {block "claimReview"}{/block}
  </head>

  <body>
    {include "bits/googleTagManagerBody.tpl"}

    <header>
      {include "bits/navmenu.tpl"}
    </header>

    <main class="container-fluid">
      {block "content"}{/block}
      {include "bs/snackbars.tpl"}
    </main>

    <footer>
      <div class="text-center container mt-3">

        <ul class="list-inline list-inline-bullet">
          <li class="list-inline-item">
            <a href="{Router::link('aggregate/about')}">
              {cap}{t}link-about{/t}{/cap}
            </a>
          </li>
          <li class="list-inline-item">
            <a href="{Router::link('aggregate/contact')}">
              {cap}{t}link-contact{/t}{/cap}
            </a>
          </li>
          {if Config::FACEBOOK_URL}
            <li class="list-inline-item">
              <a href="{Config::FACEBOOK_URL}">
                Facebook
              </a>
            </li>
          {/if}
          {if Config::LINKEDIN_URL}
            <li class="list-inline-item">
              <a href="{Config::LINKEDIN_URL}">
                LinkedIn
              </a>
            </li>
          {/if}
        </ul>

        <p>
          © 2019-{$copyrightYear} <a href="https://dignitas.ro/">Dignitas.ro</a>,
          {t}info-copyright{/t}
        </p>

      </div>
    </footer>

    {include "bits/debugInfo.tpl"}

  </body>

</html>
