{extends "layout.tpl"}

{block "title"}{t}title-region{/t}: {$region->name}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-4">
      {include "bits/regionAncestors.tpl" root=true}
    </h1>

    {if User::isModerator()}
      <p>
        <a href="{Router::link('region/edit')}/{$region->id}" class="btn btn-sm btn-primary">
          {t}link-edit{/t}
        </a>
      </p>
    {/if}

    {if count($entities)}
      <h4 class="mt-5 capitalize-first-word">
        {t}title-entities{/t}
      </h4>

      <div id="entity-list-wrapper">
        {include "bits/entityList.tpl"}
      </div>

      {include "bits/paginationWrapper.tpl"
        n=$entityPages
        url="{Config::URL_PREFIX}ajax/get-region-entities/{$region->id}"
        target="#entity-list-wrapper"}
    {/if}

    {if count($statements)}
      <h4 class="mt-5 capitalize-first-word">
        {t}title-statements{/t}
      </h4>

      <div id="statement-list-wrapper">
        {include "bits/statementList.tpl"}
      </div>

      {include "bits/paginationWrapper.tpl"
        n=$statementPages
        url="{Config::URL_PREFIX}ajax/get-region-statements/{$region->id}"
        target="#statement-list-wrapper"}
    {/if}
  </div>
{/block}
