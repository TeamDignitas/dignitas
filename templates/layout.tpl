{$pageType=$pageType|default:'other'}
<!DOCTYPE html>
<html>

  <head>
    <title>{block "title"}{/block} | Dignitas</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="{$cssFile.path}?v={$cssFile.date}" rel="stylesheet" type="text/css">

    {* expose some PHP constants *}
    <script>
      const SELECT2_LOCALE = '{LocaleUtil::getSelect2Locale()}';
      const URL_PREFIX = '{Config::URL_PREFIX}';
      const UPLOAD_MIME_TYPES = JSON.parse('{Util::getUploadMimeTypes()|json_encode}');
    </script>

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

        <div id="footerLinks" class="text-right">
          <ul class="list-inline">
            <li class="list-inline-item">
              © 2019 <a href="https://catalin.francu.com/">Cătălin Frâncu</a>
            </li>
            <li class="list-inline-item">
              <a href="https://github.com/CatalinFrancu/dignitas">GitHub</a>
            </li>
          </ul>
        </div>

      </footer>
    </div>
  </body>

</html>
