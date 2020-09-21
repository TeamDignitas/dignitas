{extends "layout.tpl"}

{block "title"}{t}title-search-results{/t}{/block}

{block "content"}
  <div class="container my-5">
    {if count($results.entities)}
      <h3>
        {t count=count($results.entities) 1=count($results.entities) plural="title-entities-plural"}
        title-entities-singular
        {/t}
      </h3>

      {foreach $results.entities as $e}
        <div class="clearfix">
          {include "bits/image.tpl"
            obj=$e
            geometry=Config::THUMB_ENTITY_SMALL
            imgClass="pic float-right"}

          {include "bits/entityLink.tpl" e=$e}
          <div>{$e->getEntityType()->name|escape}</div>
        </div>
        <hr>
      {/foreach}
    {/if}

    {if count($results.statements)}
      <h3>{t}title-statements{/t}</h3>

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

    {if empty($results.entities) && empty($results.statements) && empty($results.tags)}
      <h3>{t 1=$query|escape}info-no-search-results{/t}
    {/if}
  </div>
{/block}
