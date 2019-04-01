{extends "layout.tpl"}

{block "title"}{cap}{t}page not found{/t}{/cap}{/block}

{block "content"}
  <h3>{cap}{t}page not found{/t}{/cap}</h3>

  {t 1=Config::URL_PREFIX}
  We're sorry, the page you requested does not exist.
  Please ensure you typed in the correct address or go back to the
  <a href="%1">home page</a>.
  {/t}

{/block}
