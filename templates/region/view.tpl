{extends "layout.tpl"}

{block "title"}{t}title-region{/t}: {$region->name}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-4">
      {include "bits/regionAncestors.tpl" root=true}
    </h1>

    {if User::may(User::PRIV_EDIT_TAG)}
      <p>
        <a href="{Router::link('region/edit')}/{$region->id}" class="btn btn-sm btn-outline-primary">
          <i class="icon icon-pencil"></i>
          {t}link-edit{/t}
        </a>
      </p>
    {/if}

    {if count($entities)}
      <h4 class="mt-5 capitalize-first-word">
        {if $entityCount > count($entities)}
          {t 1=count($entities) 2=$entityCount}title-entity-count-with-limit{/t}
        {else}
          {t 1=count($entities)}title-entity-count{/t}
        {/if}
      </h4>

      {include "bits/entityList.tpl"}
    {/if}
  </div>
{/block}
