{extends "layout.tpl"}

{block "title"}{t}search results{/t}{/block}

{block "content"}

  {if count($entities)}
    <h3>
      {t count=count($entities) 1=count($entities) plural="%1 entities"}one entity{/t}
    </h3>

    {foreach $entities as $e}
      <div class="clearfix">
        {include "bits/image.tpl"
          obj=$e
          size=Config::THUMB_ENTITY_SMALL
          imgClass="pic float-right"}

        {include "bits/entityLink.tpl" e=$e}
        <div>{$e->getTypeName()}</div>
      </div>
      <hr>
    {/foreach}
  {/if}

  {if count($tags)}
    <h3>
      {t count=count($tags) 1=count($tags) plural="%1 tags"}one tag{/t}
    </h3>

    {foreach $tags as $t}
      {include "bits/tag.tpl" link=true}
    {/foreach}
  {/if}

{/block}
