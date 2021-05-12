{extends "layout.tpl"}

{block "title"}{t}title-search-results{/t}{/block}

{block "content"}
  <div class="container my-5 tabs-wrapper">

    {if !$results.empty}
      <nav class="nav nav-pills mt-5 pt-5 activate-first-tab">
        {if count($results.entities)}
          <a class="nav-link text-capitalize" id="entities-tab" data-toggle="tab" role="tab" aria-controls="results-entities"
             href="#results-entities">
            {t}title-entities{/t}
          </a>
        {/if}

        {if count($results.statements)}
          <a class="nav-link text-capitalize" id="statements-tab" data-toggle="tab" role="tab" aria-controls="results-statements"
             href="#results-statements">
            {t}title-statements{/t}
          </a>
        {/if}

        {if count($results.regions)}
          <a class="nav-link text-capitalize" id="regions-tab" data-toggle="tab" role="tab" aria-controls="results-regions"
             href="#results-regions">
            {t}title-regions{/t}
          </a>
        {/if}

        {if count($results.tags)}
          <a class="nav-link text-capitalize" id="tags-tab" data-toggle="tab" role="tab" aria-controls="results-tags"
             href="#results-tags">
            {t}title-tags{/t}
          </a>
        {/if}
      </nav>
    {else}
      <h5 class="pt-5">{t 1=$query|escape}info-no-search-results{/t}</h5>
    {/if}


    <div class="tab-content my-5">
      {if count($results.entities)}
        <div id="results-entities" class="tab-pane fade show active" role="tabpanel" aria-labelledby="entities-tab">
          {include "bits/entityFilters.tpl" term=$query}

          <div id="entity-list-wrapper" class="row mt-4">
            {include "bits/entityList.tpl" entities=$results.entities}
          </div>

          {include "bits/paginationWrapper.tpl"
            n=$results.numEntityPages
            url="{Config::URL_PREFIX}ajax/search-entities"
            target="#entity-list-wrapper"}
        </div>
      {/if}

      {if count($results.statements)}
        <div id="results-statements" class="tab-pane fade" role="tabpanel" aria-labelledby="statements-tab">
          {include "bits/statementFilters.tpl" term=$query}

          <div id="statement-list-wrapper">
            {include "bits/statementList.tpl" statements=$results.statements}
          </div>

          {include "bits/paginationWrapper.tpl"
            n=$results.numStatementPages
            url="{Config::URL_PREFIX}ajax/search-statements"
            target="#statement-list-wrapper"}
        </div>
      {/if}

      {if count($results.regions)}
        <div id="results-regions" class="tab-pane fade" role="tabpanel" aria-labelledby="regions-tab">
          {foreach $results.regions as $r}
            {include "bits/regionAncestors.tpl" region=$r link=true}
          {/foreach}
        </div>
      {/if}

      {if count($results.tags)}
        <div id="results-tags" class="tab-pane fade" role="tabpanel" aria-labelledby="tags-tab">
          {foreach $results.tags as $t}
            {include "bits/tag.tpl" link=true}
          {/foreach}
        </div>
      {/if}
    </div>

  </div>
{/block}
