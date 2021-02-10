{extends "layout.tpl"}

{block "title"}{t}title-search-results{/t}{/block}

{block "content"}
  <div class="container my-5">
    {if count($results.entities)}
      <h3 class="text-capitalize mb-4">{t}title-entities{/t}</h3>

      <div>
        {include "bits/entityFilters.tpl" term=$query}

        <div id="entity-list-wrapper" class="row mt-4">
          {include "bits/entityList.tpl" entities=$results.entities}
        </div>

        {include "bits/paginationWrapper.tpl"
          n=$results.numEntityPages
          k=1
          url="{Config::URL_PREFIX}ajax/search-entities"
          target="#entity-list-wrapper"}
      </div>
    {/if}

    {if count($results.statements)}
      <h3 class="mt-5 mb-4 text-capitalize">{t}title-statements{/t}</h3>

      <div>
        {include "bits/statementFilters.tpl" term=$query}

        <div id="statement-list-wrapper">
          {include "bits/statementList.tpl" statements=$results.statements}
        </div>

        {include "bits/paginationWrapper.tpl"
          n=$results.numStatementPages
          k=1
          url="{Config::URL_PREFIX}ajax/search-statements"
          target="#statement-list-wrapper"}
      </div>
    {/if}

    {if count($results.regions)}
      <h3>
        {t
          count=count($results.regions)
          1=count($results.regions)
          plural="title-regions-plural"}
        title-regions-singular
        {/t}
      </h3>

      {foreach $results.regions as $r}
        {include "bits/regionAncestors.tpl" region=$r link=true}
      {/foreach}
    {/if}

    {if count($results.tags)}
      <h3>
        {t count=count($results.tags) 1=count($results.tags) plural="title-tags-plural"}
        title-tags-singular
        {/t}
      </h3>

      {foreach $results.tags as $t}
        {include "bits/tag.tpl" link=true}
      {/foreach}
    {/if}

    {if $results.empty}
      <h3>{t 1=$query|escape}info-no-search-results{/t}
    {/if}
  </div>
{/block}
