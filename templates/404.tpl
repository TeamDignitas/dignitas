{extends "layout.tpl"}

{block "title"}{cap}{t}title-page-not-found{/t}{/cap}{/block}

{block "content"}
  <div class="container mt-4">
    <h3>{cap}{t}title-page-not-found{/t}{/cap}</h3>

    {t 1=Config::URL_PREFIX}info-page-not-found{/t}
  </div>
{/block}
