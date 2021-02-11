{extends "layout.tpl"}

{block "title"}{t}title-search-results{/t}{/block}

{block "content"}
  <div class="container my-5 search-results">

    <ul class="nav nav-pills flex-column flex-sm-row mt-5 pt-5">
      {if count($results.entities)}
        <li class="nav-item">
          <a class="flex-sm-fill text-sm-center nav-link active text-capitalize" id="entities-tab" data-toggle="tab" role="tab" aria-controls="results-entities" href="#results-entities">{t}title-entities{/t}</a>
        </li>
      {/if}

      {if count($results.statements)}
        <li class="nav-item">
          <a class="flex-sm-fill text-sm-center nav-link text-capitalize" id="statements-tab" data-toggle="tab" role="tab" aria-controls="results-statements" href="#results-statements">{t}title-statements{/t}</a>
        </li>
      {/if}

      {if count($results.regions)}
        <li class="nav-item">
          <a class="flex-sm-fill text-sm-center nav-link text-capitalize" id="regions-tab" data-toggle="tab" role="tab" aria-controls="results-regions" href="#results-regions">
            {t
              count=count($results.regions)
              1=count($results.regions)
              plural="title-regions-plural"}
            title-regions-singular
            {/t}
          </a>
        </li>
      {/if}

      {if count($results.tags)}
        <li class="nav-item">
          <a class="flex-sm-fill text-sm-center nav-link text-capitalize" id="tags-tab" data-toggle="tab" role="tab" aria-controls="results-tags" href="#results-tags">
            {t count=count($results.tags) 1=count($results.tags) plural="title-tags-plural"}
              title-tags-singular
            {/t}
          </a>
        </li>
      {/if}
    </ul>

    {if $results.empty}
      <h3>{t 1=$query|escape}info-no-search-results{/t}</h3>
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
            k=1
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
            k=1
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
