{extends "layout.tpl"}

{block "title"}{t}title-search-results{/t}{/block}

{block "content"}

  {if count($entities)}
    <h3>
      {t count=count($entities) 1=count($entities) plural="title-entities-plural"}
      title-entities-singular
      {/t}
    </h3>

    {foreach $entities as $e}
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

  {if count($statements)}
    <h3>
      {t count=count($statements) 1=count($statements) plural="title-statements-plural"}
      title-statements-singular
      {/t}
    </h3>

    {include "bits/statementList.tpl" entityImages=false addedBy=false}
  {/if}

  {if count($tags)}
    <h3>
      {t count=count($tags) 1=count($tags) plural="title-tags-plural"}
      title-tags-singular
      {/t}
    </h3>

    {foreach $tags as $t}
      {include "bits/tag.tpl" link=true}
    {/foreach}
  {/if}

  {if empty($entities) && empty($statements) && empty($tags)}
    <h3>{t 1=$query|escape}info-no-search-results{/t}
  {/if}

{/block}
