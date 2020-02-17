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

    <div class="mt-5">
      <main class="container">
        {include "bits/flashMessages.tpl"}
        {block "content"}{/block}
      </main>
    </div>

    <footer>
      <div class="text-center container mt-3">
        <ul class="list-inline list-inline-bullet">
          <li class="list-inline-item">
            © 2019 <a href="https://catalin.francu.com/">Cătălin Frâncu</a>
          </li>
          <li class="list-inline-item">
            <a href="https://github.com/CatalinFrancu/dignitas">GitHub</a>
          </li>
        </ul>
      </div>
    </footer>
  </body>

</html>
