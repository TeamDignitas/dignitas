{extends "layout.tpl"}

{block "title"}{cap}{t}title-donate{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    {if Config::DONATE_PAGE}
      {$staticResource->getContents()}      
    {else}
      {t}info-donations-disabled{/t}
    {/if}
  </div>

  {include "bits/donateWidgets.tpl"}
{/block}
