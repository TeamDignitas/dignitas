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

    <link href="{$cssFile.path}?v={$cssFile.date}" rel="stylesheet" type="text/css">

    {include "bits/phpConstants.tpl"}
    <script src="{$jsFile.path}?v={$jsFile.date}"></script>

  </head>

  <body>

    <header>
      {include "bits/navmenu.tpl"}
    </header>

    <main class="container-fluid">
      {include "bits/flashMessages.tpl"}
      {block "content"}{/block}

      <div id="toasts" aria-live="polite" aria-atomic="true"></div>
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
