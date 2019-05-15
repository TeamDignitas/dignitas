{extends "layout.tpl"}

{block "title"}{$entity->name|escape}{/block}

{block "content"}
  <div class="clearfix">
    {if $entity->imageExtension}
      <div class="mb-2">
        {$sz=Img::getThumbSize($entity, 1)}
        <img
          src="{Img::getThumbLink($entity, 1)}"
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

    <div>
      {if User::may(User::PRIV_EDIT_ENTITY)}
        <a href="{Router::link('entity/edit')}/{$entity->id}" class="btn btn-light">
          <i class="icon icon-edit"></i>
          {t}edit{/t}
        </a>
      {/if}
    </div>
  </div>

  {if count($members)}
    <h4>{cap}{t}members{/t}{/cap}</h4>

    <ul>
      {foreach $members as $m}
        <li>
          {include "bits/entityLink.tpl" e=$m}
        </li>
      {/foreach}
    </ul>
  {/if}

  {if count($statements)}
    <h4>{cap}{t}statements{/t}{/cap}</h4>
    {include "bits/statementList.tpl" entityImages=false}
  {/if}

{/block}
