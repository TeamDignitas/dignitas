{extends "layout.tpl"}

{block "title"}{$entity->name|escape}{/block}

{block "content"}
  {include "bits/entity.tpl"}

  {if count($statements)}
    <h4>{cap}{t}statements{/t}{/cap}</h4>
    {include "bits/statementList.tpl" entityImages=false}
  {/if}

  {include "bits/flagModal.tpl"}

{/block}
