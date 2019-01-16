{$pageType=$pageType|default:'other'}
<!DOCTYPE html>
<html>

  <head>
    <title>{block "title"}home page{/block} | tbd</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="{$cssFile.path}?v={$cssFile.date}" rel="stylesheet" type="text/css">
    <script src="{$jsFile.path}?v={$jsFile.date}"></script>
  </head>

  <body>

    <header>
      {include "bits/navmenu.tpl"}
    </header>

    <div class="container mt-3">
      <main>
        {include "bits/flashMessages.tpl"}
        {block "content"}{/block}
      </main>

      <footer>

        <div class="text-center">
          © 2019 <a href="https://catalin.francu.com/">Cătălin Frâncu</a></li>
        </div>

      </footer>
    </div>
  </body>

</html>
