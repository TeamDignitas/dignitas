{$pageType=$pageType|default:'other'}
<!DOCTYPE html>
<html>

  <head>
    <title>{block "title"}home page{/block} | tbd</title>
    <meta charset="utf-8">
    <meta
      content="width=device-width, initial-scale=1.0, maximum-scale=3.0, user-scalable=yes"
      name="viewport">

    <link href="{$cssFile.path}?v={$cssFile.date}" rel="stylesheet" type="text/css">
    <script src="{$jsFile.path}?v={$jsFile.date}"></script>
  </head>

  <body>

    <header>
      {include "bits/navmenu.tpl"}
    </header>

    <div class="container">
      <main class="row">
        <div class="col-md-12 main-content">
          {include "bits/flashMessages.tpl"}
          {block "content"}{/block}
        </div>
      </main>

      <footer class="footer">

        <div class="text-center">
          © 2019 <a href="https://catalin.francu.com/">Cătălin Frâncu</a></li>
        </div>

      </footer>
    </div>
  </body>

</html>
