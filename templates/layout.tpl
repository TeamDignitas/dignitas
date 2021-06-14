{$pageType=$pageType|default:'other'}
<!DOCTYPE html>
<html>

  <head>
    <title>
      {block "title"}{/block}
      {if $pageType != 'home'}| Dignitas{/if}
    </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

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

  </head>

  <body>

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
            © 2019-{'Y'|date} <a href="https://github.com/TeamDignitas">Team Dignitas</a>
          </li>
        </ul>
      </div>
    </footer>

    {include "bits/debugInfo.tpl"}

  </body>

</html>
