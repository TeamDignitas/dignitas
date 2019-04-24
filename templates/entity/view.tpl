{extends "layout.tpl"}

{block "title"}{$entity->name|escape}{/block}

{block "content"}
  <div class="clearfix">
    {if $entity->imageExtension}
      <div class="mb-2">
        {$sz=$entity->getThumbSize(1)}
        <img
          src="{$entity->getThumbLink(1)}"
          class="img-thumbnail rounded float-right"
          width="{$sz.width}"
          height="{$sz.height}">
      </div>
    {/if}

    <h3>{$entity->name|escape}</h3>
    <h4>{$entity->getTypeName()}</h4>

    <ul>
      {foreach $relations as $r}
        <li>
          {include "bits/relation.tpl"}
        </li>
      {/foreach}
    </ul>
  </div>

  {if count($statements)}
    <h4>{cap}{t}statements{/t}{/cap}</h4>
    {include "bits/statementList.tpl" entityImages=false}
  {/if}

{/block}
